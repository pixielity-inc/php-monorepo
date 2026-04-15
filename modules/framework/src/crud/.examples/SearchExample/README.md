# Search System Example

A complete example showing how the Pixielity search system works across the
Database and CRUD packages, using a Product model with Meilisearch.

## Two Search Systems, One Attribute

```
#[Searchable(['name', 'description', 'sku'])]
```

This single attribute powers both:

1. **Scout (full-text)** — Meilisearch/Algolia indexing via `HasSearch` trait on
   the model
2. **SQL (LIKE)** — `?search=term` via `RequestSearchCriteria` on the repository

## Architecture

```
┌─────────────────────────────────────────────────────────┐
│                     API Request                          │
│  GET /api/products?search=laptop&filters[status][$eq]=active │
└──────────────┬──────────────────────────────────────────┘
               │
       ┌───────▼───────┐
       │  Controller    │
       └───────┬───────┘
               │
       ┌───────▼───────┐     ┌──────────────────────────┐
       │  Repository    │────▶│ RequestSearchCriteria     │
       │  ->search()    │     │ SQL: WHERE name LIKE '%…' │
       │  ->filter()    │     │ OR description LIKE '%…'  │
       │  ->paginate()  │     └──────────────────────────┘
       └───────┬───────┘
               │
       ┌───────▼───────┐     ┌──────────────────────────┐
       │  Model         │────▶│ HasSearch (Scout)         │
       │  ::search()    │     │ Meilisearch full-text     │
       └───────────────┘     └──────────────────────────┘
```

## When to Use Which

| Use Case                          | System     | Method                             |
| --------------------------------- | ---------- | ---------------------------------- |
| API `?search=` query param        | SQL (LIKE) | `$repo->search()->paginate()`      |
| Full-text search with relevance   | Scout      | `Product::search('laptop')->get()` |
| Typo-tolerant search              | Scout      | `Product::search('lapto')->get()`  |
| Faceted search / filters          | Scout      | `Product::search('')->where(...)`  |
| Simple DB search, no engine       | SQL (LIKE) | `$repo->search()->get()`           |
| Admin search with complex filters | SQL (LIKE) | `$repo->filter()->search()->get()` |

## Files in This Example

| File                    | Purpose                                    |
| ----------------------- | ------------------------------------------ |
| `ProductInterface.php`  | ATTR\_\* constants                         |
| `Product.php`           | Model with HasSearch trait (Scout)         |
| `ProductRepository.php` | Repository with #[Searchable] (SQL search) |
| `ProductController.php` | Controller showing both search methods     |
| `config/scout.php`      | Meilisearch configuration                  |
