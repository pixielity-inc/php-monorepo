# Package Architecture: Database & CRUD

## Package Boundaries

```
┌─────────────────────────────────────────────────────────────────┐
│                        DATABASE PACKAGE                          │
│                  pixielity/laravel-database                       │
│                                                                  │
│  Owns: Model, Schema, Storage                                    │
│  "What the data IS"                                              │
│                                                                  │
│  ┌──────────┐  ┌──────────────┐  ┌───────────────┐              │
│  │  Model    │  │  Schema      │  │  Concerns     │              │
│  │  (base)   │  │  (Blueprint) │  │  (traits)     │              │
│  └──────────┘  └──────────────┘  └───────────────┘              │
└─────────────────────────────────────────────────────────────────┘
                              │
                              │ extends / uses
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                         CRUD PACKAGE                             │
│                    pixielity/laravel-crud                         │
│                                                                  │
│  Owns: Repository, Service, Criteria, Scopes, Events            │
│  "How the data is QUERIED and MANAGED"                           │
│                                                                  │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐        │
│  │Repository │  │ Service  │  │ Criteria │  │  Scopes  │        │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘        │
└─────────────────────────────────────────────────────────────────┘
```

## The Rule

> **Model = what the data IS. Repository = how the data is QUERIED.**

The model is a pure schema object. It defines table, columns, casts,
relationships, and data-level behaviors (translations, metadata, search
indexing). It has ZERO query logic — no scopes, no filtering, no sorting, no
caching.

The repository owns ALL query logic. It defines how data is fetched, filtered,
sorted, paginated, cached, and scoped. It uses criteria and scopes to compose
reusable query patterns.

---

## Database Package — What It Owns

### Base Model (`Pixielity\Database\Model`)

- Extends `Illuminate\Database\Eloquent\Model`
- Uses `HasDeprecatedMethods` (discourages direct `Model::find()`,
  `Model::create()`)
- Uses `HasDeprecatedProperties` (all Eloquent properties marked
  `#[Deprecated]`)
- Provides `casts()` method as the only override point

### Model Traits (data-level behaviors)

| Trait              | Purpose                                                                                                             | When to Use                                          |
| ------------------ | ------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------- |
| `HasTranslations`  | Spatie proxy — reads `#[Translatable]` attribute, handles `getAttribute()`/`setAttribute()` for JSON locale columns | Model has translatable fields stored as JSON         |
| `HasMetadata`      | Key-value metadata (COLUMN/TABLE/PIVOT strategies) — `getMeta()`, `setMeta()`, `hasMeta()`, query scopes            | Model needs arbitrary key-value data                 |
| `HasSearch`        | Laravel Scout integration — reads `#[Searchable]` attribute, builds indexable array, configures engine/index        | Model needs full-text search via Meilisearch/Algolia |
| `HasModelExtender` | Magento-style magic getters/setters (`$model->getName()`, `$model->setName()`)                                      | Always enabled on base Model                         |

### Schema Blueprints (migration macros)

| Macro                                               | Usage               | What It Creates                                            |
| --------------------------------------------------- | ------------------- | ---------------------------------------------------------- |
| `$table->metadatable()`                             | `MetadataBlueprint` | Nullable JSON column for inline metadata (COLUMN strategy) |
| `MetadataBlueprint::createMetadataTable('tenants')` | Static helper       | Dedicated `tenants_metadata` table (TABLE strategy)        |

### Contracts

| Interface              | Purpose                                                        |
| ---------------------- | -------------------------------------------------------------- |
| `HasMetadataInterface` | ATTR\_\* constants + method signatures for metadata operations |
| `ModelInterface`       | Base Eloquent method signatures for interface-typed variables  |

### Enums

| Enum               | Purpose                                         |
| ------------------ | ----------------------------------------------- |
| `MetadataStrategy` | COLUMN / TABLE / PIVOT — how metadata is stored |
| `SearchEngine`     | COLLECTION / MEILISEARCH / ALGOLIA / etc.       |

---

## CRUD Package — What It Owns

### Repository (`Pixielity\Crud\Repositories\Repository`)

- Abstract base class for all data access
- Uses 7 concern traits (see below)
- Provides `query()` method — returns a prepared Builder with criteria, scope,
  relations, ordering applied
- All read methods are one-liners: `$this->query()->get()`,
  `$this->query()->find($id)`
- Write methods dispatch events and are the only place mutations happen

### Repository Concern Traits

| Trait                 | Purpose                                                                               |
| --------------------- | ------------------------------------------------------------------------------------- |
| `HasCriteria`         | Criteria stack management — push, pop, reset, skip                                    |
| `HasQueryModifiers`   | `with()`, `withCount()`, `orderBy()`, `scopeQuery()` — per-query overrides            |
| `HasRequestFiltering` | `filter()`, `sort()`, `search()` — reads from HTTP request query params               |
| `HasTranslatable`     | Locale-aware column qualification (`name` → `name->en`)                               |
| `HasEvents`           | `fire()` — dispatches EntityCreated/Updated/Deleted events                            |
| `BootsFromRegistry`   | Loads pre-resolved attribute config from RepositoryConfigRegistry at construction     |
| `PreparesQueries`     | `prepareQuery()` / `resetAfterQuery()` — applies criteria, scope, relations, ordering |

### Service (`Pixielity\Crud\Services\Service`)

- Abstract base class for business logic
- Delegates to repository via `#[UseRepository]` attribute
- Provides pass-through CRUD methods + custom business logic

### Criteria (reusable query filters)

| Criteria                | Purpose                                                             |
| ----------------------- | ------------------------------------------------------------------- |
| `RequestFilterCriteria` | `?filters[field][$operator]=value` — 23 Purity-compatible operators |
| `RequestSortCriteria`   | `?sort=field:direction` — request-based sorting                     |
| `RequestSearchCriteria` | `?search=term` — SQL LIKE search across `#[Searchable]` fields      |
| `TranslatableCriteria`  | Rewrites ORDER BY for translatable JSON columns                     |
| `MetadataCriteria`      | `?meta[key]=value` — metadata-aware filtering                       |
| `OrderByCriteria`       | Programmatic ordering                                               |
| `WhereCriteria`         | Programmatic filtering                                              |

### Scopes (global Eloquent scopes)

| Scope                 | Purpose                                |
| --------------------- | -------------------------------------- |
| `ActiveScope`         | `WHERE status = 'active'`              |
| `PublishedScope`      | `WHERE published_at IS NOT NULL`       |
| `VerifiedScope`       | `WHERE verified_at IS NOT NULL`        |
| `FeaturedScope`       | `WHERE is_featured = true`             |
| `ExcludeDeletedScope` | `WHERE deleted_at IS NULL`             |
| `RecentScope`         | `WHERE created_at >= now() - interval` |
| `OfTypeScope`         | `WHERE type = ?`                       |
| `TenantScope`         | `WHERE tenant_id = ?`                  |

### Registries (boot-time config storage)

| Registry                   | Purpose                                                                         |
| -------------------------- | ------------------------------------------------------------------------------- |
| `RepositoryConfigRegistry` | Stores pre-resolved attribute configs per repository (Octane-safe, `#[Scoped]`) |
| `CriteriaRegistry`         | Stores discovered criteria classes                                              |
| `ScopeRegistry`            | Stores discovered scope classes                                                 |

### Events

| Event           | Dispatched When         |
| --------------- | ----------------------- |
| `EntityCreated` | `$repository->create()` |
| `EntityUpdated` | `$repository->update()` |
| `EntityDeleted` | `$repository->delete()` |

---

## Attribute Ownership — Where Each Attribute Lives

### On the MODEL (data-level, "what the data IS")

| Attribute                                             | Package    | Purpose                            |
| ----------------------------------------------------- | ---------- | ---------------------------------- |
| `#[Table('products')]`                                | Laravel 13 | Table name                         |
| `#[Unguarded]`                                        | Laravel 13 | Disable mass assignment protection |
| `#[ObservedBy(ProductObserver::class)]`               | Laravel 13 | Model observers                    |
| `#[ScopedBy(ActiveScope::class)]`                     | Laravel 13 | Global scopes (Laravel native)     |
| `#[CollectedBy(ProductCollection::class)]`            | Laravel 13 | Custom collection class            |
| `#[UseFactory(ProductFactory::class)]`                | Laravel 13 | Model factory                      |
| `#[Searchable(fields: [...], engine: 'meilisearch')]` | CRUD       | Searchable fields + Scout config   |
| `#[Translatable(['name', 'description'])]`            | CRUD       | Translatable fields                |

### On the REPOSITORY (query-level, "how the data is QUERIED")

| Attribute                                             | Package | Purpose                              |
| ----------------------------------------------------- | ------- | ------------------------------------ |
| `#[AsRepository]`                                     | CRUD    | Marks class for auto-discovery       |
| `#[UseModel(ProductInterface::class)]`                | CRUD    | Binds repository to model            |
| `#[WithRelations('category', 'tags')]`                | CRUD    | Default eager loading                |
| `#[WithCount('reviews')]`                             | CRUD    | Default withCount                    |
| `#[OrderBy(column: 'created_at', direction: 'desc')]` | CRUD    | Default ordering (repeatable)        |
| `#[Filterable([...])]`                                | CRUD    | Request-based filtering config       |
| `#[Sortable([...])]`                                  | CRUD    | Request-based sorting config         |
| `#[UseScope(ActiveScope::class)]`                     | CRUD    | Global scopes applied via repository |
| `#[UseCriteria(ActiveCriteria::class)]`               | CRUD    | Default criteria                     |
| `#[UseQueryScope('published')]`                       | CRUD    | Named query scopes                   |
| `#[Metadatable(column: 'metadata')]`                  | CRUD    | Metadata-aware filtering config      |

### On the SERVICE

| Attribute                                             | Package | Purpose                     |
| ----------------------------------------------------- | ------- | --------------------------- |
| `#[UseRepository(ProductRepositoryInterface::class)]` | CRUD    | Auto-resolves repository    |
| `#[UseResource(ProductResource::class)]`              | CRUD    | API resource transformation |
| `#[UseData(ProductData::class)]`                      | CRUD    | DTO transformation          |

### On the CONTROLLER

| Attribute                                       | Package | Purpose               |
| ----------------------------------------------- | ------- | --------------------- |
| `#[UseService(ProductServiceInterface::class)]` | CRUD    | Auto-resolves service |

---

## Discovery & Booting Flow

```
Application Boot
  │
  ├── CrudServiceProvider::boot()
  │     │
  │     ├── discoverRepositories()
  │     │     │
  │     │     ├── Find all classes with #[AsRepository]
  │     │     │   (via pixielity/laravel-discovery cached file)
  │     │     │
  │     │     ├── For each repository class:
  │     │     │     ├── Read #[UseModel] → get model class
  │     │     │     ├── Read #[WithRelations], #[WithCount], #[OrderBy] from REPO
  │     │     │     ├── Read #[Filterable], #[Sortable] from REPO
  │     │     │     ├── Read #[UseScope], #[UseCriteria], #[UseQueryScope] from REPO
  │     │     │     ├── Read #[Searchable] from MODEL (via model class)
  │     │     │     ├── Read #[Translatable] from MODEL (via model class)
  │     │     │     └── Store all config in RepositoryConfigRegistry
  │     │     │
  │     │     └── Zero runtime reflection — all from cached attributes
  │     │
  │     ├── discoverCriteria()
  │     │     └── Find all classes with #[AsCriteria]
  │     │
  │     └── discoverScopes()
  │           └── Find all classes with #[AsScope]
  │
  └── Repository::__construct()
        │
        ├── loadConfigFromRegistry()
        │     ├── Read pre-resolved config from RepositoryConfigRegistry
        │     ├── Set $defaultWithRelations, $defaultWithCountRelations, etc.
        │     ├── Set $translatableFields (from model's #[Translatable])
        │     ├── Push default criteria from #[UseCriteria]
        │     └── Store pending scopes from #[UseScope]
        │
        └── makeModelWithScopes()
              ├── Resolve model class from #[UseModel]
              ├── Create model instance
              └── Apply pending global scopes
```

---

## When to Use Model Traits vs Repository Attributes

### Use a MODEL TRAIT when:

- The behavior is about **data representation** (how data is read/written at the
  attribute level)
- It overrides `getAttribute()` / `setAttribute()` (translations, metadata)
- It integrates with an external system at the model level (Scout indexing)
- It adds relationships to the model (metadata morphMany)
- It adds query scopes that are model-intrinsic (not query-pattern-specific)

Examples: `HasTranslations`, `HasMetadata`, `HasSearch`, `SoftDeletes`

### Use a REPOSITORY ATTRIBUTE when:

- The behavior is about **query patterns** (how data is fetched, filtered,
  sorted)
- It configures eager loading, ordering, pagination
- It defines what API consumers can filter/sort on
- It applies reusable query criteria or scopes
- It's about the query builder, not the model instance

Examples: `#[Filterable]`, `#[Sortable]`, `#[WithRelations]`, `#[OrderBy]`,
`#[UseScope]`

### Use a MODEL ATTRIBUTE when:

- The behavior is about **data identity** (what fields exist, how they're
  stored)
- Both the model layer AND the repository layer need to read it
- It's the single source of truth that multiple systems consume

Examples: `#[Searchable]` (Scout + SQL search), `#[Translatable]` (model
read/write + repo query qualification)

---

## Complete Example: Product Module

```
Model (what the data IS):
  #[Table('products')]
  #[Unguarded]
  #[Searchable(fields: ['name', 'description', 'sku'], engine: 'meilisearch')]
  #[Translatable(['name', 'description'])]
  class Product extends Model {
      use HasSearch;
      use HasTranslations;
      use SoftDeletes;
      protected function casts(): array { ... }
      public function category(): BelongsTo { ... }
  }

Repository (how the data is QUERIED):
  #[AsRepository]
  #[UseModel(ProductInterface::class)]
  #[WithRelations('category', 'tags')]
  #[WithCount('reviews')]
  #[OrderBy(column: 'created_at', direction: 'desc')]
  #[Filterable(['name' => ['$eq', '$contains'], 'price' => '*'])]
  #[Sortable(['name', 'price', 'created_at'])]
  #[UseScope(ActiveScope::class)]
  class ProductRepository extends Repository {
      public function findFeatured(): Collection {
          return $this->query()->where('is_featured', true)->get();
      }
  }

Service (business logic):
  #[UseRepository(ProductRepositoryInterface::class)]
  class ProductService extends Service {
      public function publish(int $id): Model { ... }
  }

Controller (HTTP layer):
  class ProductController {
      public function index(): AnonymousResourceCollection {
          return ProductResource::collection(
              $this->service->repository()->filter()->sort()->search()->paginate()
          );
      }
  }
```
