<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum representing various styles.
 *
 * @method static ABEEZEE() Returns the ABEEZEE enum instance
 * @method static ABEL() Returns the ABEL enum instance
 * @method static ABHAYA_LIBRE() Returns the ABHAYA_LIBRE enum instance
 * @method static ABRIL_FATFACE() Returns the ABRIL_FATFACE enum instance
 * @method static ACLONICA() Returns the ACLONICA enum instance
 * @method static ACME() Returns the ACME enum instance
 * @method static ACTOR() Returns the ACTOR enum instance
 * @method static ADAMINA() Returns the ADAMINA enum instance
 * @method static ADVENT_PRO() Returns the ADVENT_PRO enum instance
 * @method static AGUAFINA_SCRIPT() Returns the AGUAFINA_SCRIPT enum instance
 * @method static AKRONIM() Returns the AKRONIM enum instance
 * @method static ALADIN() Returns the ALADIN enum instance
 * @method static ALDRICH() Returns the ALDRICH enum instance
 * @method static ALEF() Returns the ALEF enum instance
 * @method static ALEGREYA() Returns the ALEGREYA enum instance
 * @method static ALEGREYA_SC() Returns the ALEGREYA_SC enum instance
 * @method static ALEGREYA_SANS() Returns the ALEGREYA_SANS enum instance
 * @method static ALEGREYA_SANS_SC() Returns the ALEGREYA_SANS_SC enum instance
 * @method static ALEX_BRUSH() Returns the ALEX_BRUSH enum instance
 * @method static ALFA_SLAB_ONE() Returns the ALFA_SLAB_ONE enum instance
 * @method static ALICE() Returns the ALICE enum instance
 * @method static ALIKE() Returns the ALIKE enum instance
 * @method static ALIKE_ANGULAR() Returns the ALIKE_ANGULAR enum instance
 * @method static ALLAN() Returns the ALLAN enum instance
 * @method static ALLERTA() Returns the ALLERTA enum instance
 * @method static ALLERTA_STENCIL() Returns the ALLERTA_STENCIL enum instance
 * @method static ALLURA() Returns the ALLURA enum instance
 * @method static ALMENDRA() Returns the ALMENDRA enum instance
 * @method static ALMENDRA_DISPLAY() Returns the ALMENDRA_DISPLAY enum instance
 * @method static ALMENDRA_SC() Returns the ALMENDRA_SC enum instance
 * @method static AMARANTE() Returns the AMARANTE enum instance
 * @method static AMARANTH() Returns the AMARANTH enum instance
 * @method static AMATIC_SC() Returns the AMATIC_SC enum instance
 * @method static AMETHYSTA() Returns the AMETHYSTA enum instance
 * @method static AMIKO() Returns the AMIKO enum instance
 * @method static AMIRI() Returns the AMIRI enum instance
 * @method static AMITA() Returns the AMITA enum instance
 * @method static ANAHEIM() Returns the ANAHEIM enum instance
 * @method static ANDADA() Returns the ANDADA enum instance
 * @method static ANDIKA() Returns the ANDIKA enum instance
 * @method static ANGKOR() Returns the ANGKOR enum instance
 * @method static ANNIE_USE_YOUR_TELESCOPE() Returns the ANNIE_USE_YOUR_TELESCOPE enum instance
 * @method static ANONYMOUS_PRO() Returns the ANONYMOUS_PRO enum instance
 * @method static ANTIC() Returns the ANTIC enum instance
 * @method static ANTIC_DIDONE() Returns the ANTIC_DIDONE enum instance
 * @method static ANTIC_SLAB() Returns the ANTIC_SLAB enum instance
 * @method static ANTON() Returns the ANTON enum instance
 * @method static ARAPEY() Returns the ARAPEY enum instance
 * @method static ARBUTUS() Returns the ARBUTUS enum instance
 * @method static ARBUTUS_SLAB() Returns the ARBUTUS_SLAB enum instance
 * @method static ARCHITECTS_DAUGHTER() Returns the ARCHITECTS_DAUGHTER enum instance
 * @method static ARCHIVO() Returns the ARCHIVO enum instance
 * @method static ARCHIVO_BLACK() Returns the ARCHIVO_BLACK enum instance
 * @method static ARCHIVO_NARROW() Returns the ARCHIVO_NARROW enum instance
 * @method static AREF_RUQAA() Returns the AREF_RUQAA enum instance
 * @method static ARIMA_MADURAI() Returns the ARIMA_MADURAI enum instance
 * @method static ARIMO() Returns the ARIMO enum instance
 * @method static ARIZONIA() Returns the ARIZONIA enum instance
 * @method static ARMATA() Returns the ARMATA enum instance
 * @method static ARSENAL() Returns the ARSENAL enum instance
 * @method static ARTIFIKA() Returns the ARTIFIKA enum instance
 * @method static ARVO() Returns the ARVO enum instance
 * @method static ARYA() Returns the ARYA enum instance
 * @method static ASAP() Returns the ASAP enum instance
 * @method static ASAP_CONDENSED() Returns the ASAP_CONDENSED enum instance
 * @method static ASAR() Returns the ASAR enum instance
 * @method static ASSET() Returns the ASSET enum instance
 * @method static ASSISTANT() Returns the ASSISTANT enum instance
 * @method static ASTLOCH() Returns the ASTLOCH enum instance
 * @method static ASUL() Returns the ASUL enum instance
 * @method static ATHITI() Returns the ATHITI enum instance
 * @method static ATMA() Returns the ATMA enum instance
 * @method static ATOMIC_AGE() Returns the ATOMIC_AGE enum instance
 * @method static AUBREY() Returns the AUBREY enum instance
 * @method static AUDIOWIDE() Returns the AUDIOWIDE enum instance
 * @method static AUTOUR_ONE() Returns the AUTOUR_ONE enum instance
 * @method static AVERAGE() Returns the AVERAGE enum instance
 * @method static AVERAGE_SANS() Returns the AVERAGE_SANS enum instance
 * @method static AVERIA_GRUESA_LIBRE() Returns the AVERIA_GRUESA_LIBRE enum instance
 * @method static AVERIA_LIBRE() Returns the AVERIA_LIBRE enum instance
 * @method static AVERIA_SANS_LIBRE() Returns the AVERIA_SANS_LIBRE enum instance
 * @method static AVERIA_SERIF_LIBRE() Returns the AVERIA_SERIF_LIBRE enum instance
 * @method static BAD_SCRIPT() Returns the BAD_SCRIPT enum instance
 * @method static BAHIANA() Returns the BAHIANA enum instance
 * @method static BALOO() Returns the BALOO enum instance
 * @method static BALOO_BHAI() Returns the BALOO_BHAI enum instance
 * @method static BALOO_BHAIJAAN() Returns the BALOO_BHAIJAAN enum instance
 * @method static BALOO_BHAINA() Returns the BALOO_BHAINA enum instance
 * @method static BALOO_CHETTAN() Returns the BALOO_CHETTAN enum instance
 * @method static BALOO_DA() Returns the BALOO_DA enum instance
 * @method static BALOO_PAAJI() Returns the BALOO_PAAJI enum instance
 * @method static BALOO_TAMMA() Returns the BALOO_TAMMA enum instance
 * @method static BALOO_TAMMUDU() Returns the BALOO_TAMMUDU enum instance
 * @method static BALOO_THAMBI() Returns the BALOO_THAMBI enum instance
 * @method static BALTHAZAR() Returns the BALTHAZAR enum instance
 * @method static BANGERS() Returns the BANGERS enum instance
 * @method static BARLOW() Returns the BARLOW enum instance
 * @method static BARLOW_CONDENSED() Returns the BARLOW_CONDENSED enum instance
 * @method static BARLOW_SEMI_CONDENSED() Returns the BARLOW_SEMI_CONDENSED enum instance
 * @method static BARRIO() Returns the BARRIO enum instance
 * @method static BASIC() Returns the BASIC enum instance
 * @method static BATTAMBANG() Returns the BATTAMBANG enum instance
 * @method static BAUMANS() Returns the BAUMANS enum instance
 * @method static BAYON() Returns the BAYON enum instance
 * @method static BELGRANO() Returns the BELGRANO enum instance
 * @method static BELLEFAIR() Returns the BELLEFAIR enum instance
 * @method static BELLEZA() Returns the BELLEZA enum instance
 * @method static BENCHNINE() Returns the BENCHNINE enum instance
 * @method static BENTHAM() Returns the BENTHAM enum instance
 * @method static BERKSHIRE_SWASH() Returns the BERKSHIRE_SWASH enum instance
 * @method static BEVAN() Returns the BEVAN enum instance
 * @method static BIGELOW_RULES() Returns the BIGELOW_RULES enum instance
 * @method static BIGSHOT_ONE() Returns the BIGSHOT_ONE enum instance
 * @method static BILBO() Returns the BILBO enum instance
 * @method static BILBO_SWASH_CAPS() Returns the BILBO_SWASH_CAPS enum instance
 * @method static BIORHYME() Returns the BIORHYME enum instance
 * @method static BIORHYME_EXPANDED() Returns the BIORHYME_EXPANDED enum instance
 * @method static BIRYANI() Returns the BIRYANI enum instance
 * @method static BITTER() Returns the BITTER enum instance
 * @method static BLACK_OPS_ONE() Returns the BLACK_OPS_ONE enum instance
 * @method static BOKOR() Returns the BOKOR enum instance
 * @method static BONBON() Returns the BONBON enum instance
 * @method static BOOGALOO() Returns the BOOGALOO enum instance
 * @method static BOWLBY_ONE() Returns the BOWLBY_ONE enum instance
 * @method static BOWLBY_ONE_SC() Returns the BOWLBY_ONE_SC enum instance
 * @method static BRAWLER() Returns the BRAWLER enum instance
 * @method static BREE_SERIF() Returns the BREE_SERIF enum instance
 * @method static BUBBLEGUM_SANS() Returns the BUBBLEGUM_SANS enum instance
 * @method static BUBBLER_ONE() Returns the BUBBLER_ONE enum instance
 * @method static BUDA() Returns the BUDA enum instance
 * @method static BUENARD() Returns the BUENARD enum instance
 * @method static BUNGEE() Returns the BUNGEE enum instance
 * @method static BUNGEE_HAIRLINE() Returns the BUNGEE_HAIRLINE enum instance
 * @method static BUNGEE_INLINE() Returns the BUNGEE_INLINE enum instance
 * @method static BUNGEE_OUTLINE() Returns the BUNGEE_OUTLINE enum instance
 * @method static BUNGEE_SHADE() Returns the BUNGEE_SHADE enum instance
 * @method static BUTCHERMAN() Returns the BUTCHERMAN enum instance
 * @method static BUTTERFLY_KIDS() Returns the BUTTERFLY_KIDS enum instance
 * @method static CABIN() Returns the CABIN enum instance
 * @method static CABIN_CONDENSED() Returns the CABIN_CONDENSED enum instance
 * @method static CABIN_SKETCH() Returns the CABIN_SKETCH enum instance
 * @method static CAESAR_DRESSING() Returns the CAESAR_DRESSING enum instance
 * @method static CAGLIOSTRO() Returns the CAGLIOSTRO enum instance
 * @method static CAIRO() Returns the CAIRO enum instance
 * @method static CALLIGRAFFITTI() Returns the CALLIGRAFFITTI enum instance
 * @method static CAMBAY() Returns the CAMBAY enum instance
 * @method static CAMBO() Returns the CAMBO enum instance
 * @method static CANDAL() Returns the CANDAL enum instance
 * @method static CANTARELL() Returns the CANTARELL enum instance
 * @method static CANTATA_ONE() Returns the CANTATA_ONE enum instance
 * @method static CANTORA_ONE() Returns the CANTORA_ONE enum instance
 * @method static CAPRIOLA() Returns the CAPRIOLA enum instance
 * @method static CARDO() Returns the CARDO enum instance
 * @method static CARME() Returns the CARME enum instance
 * @method static CARROIS_GOTHIC() Returns the CARROIS_GOTHIC enum instance
 * @method static CARROIS_GOTHIC_SC() Returns the CARROIS_GOTHIC_SC enum instance
 * @method static CARTER_ONE() Returns the CARTER_ONE enum instance
 * @method static CATAMARAN() Returns the CATAMARAN enum instance
 * @method static CAUDEX() Returns the CAUDEX enum instance
 * @method static CAVEAT() Returns the CAVEAT enum instance
 * @method static CAVEAT_BRUSH() Returns the CAVEAT_BRUSH enum instance
 * @method static CEDARVILLE_CURSIVE() Returns the CEDARVILLE_CURSIVE enum instance
 * @method static CEVICHE_ONE() Returns the CEVICHE_ONE enum instance
 * @method static CHANGA() Returns the CHANGA enum instance
 * @method static CHANGA_ONE() Returns the CHANGA_ONE enum instance
 * @method static CHANGO() Returns the CHANGO enum instance
 * @method static CHATHURA() Returns the CHATHURA enum instance
 * @method static CHAU_PHILOMENE_ONE() Returns the CHAU_PHILOMENE_ONE enum instance
 * @method static CHELA_ONE() Returns the CHELA_ONE enum instance
 * @method static CHELSEA_MARKET() Returns the CHELSEA_MARKET enum instance
 * @method static CHENLA() Returns the CHENLA enum instance
 * @method static CHERRY_CREAM_SODA() Returns the CHERRY_CREAM_SODA enum instance
 * @method static CHERRY_SWASH() Returns the CHERRY_SWASH enum instance
 * @method static CHEWY() Returns the CHEWY enum instance
 * @method static CHICLE() Returns the CHICLE enum instance
 * @method static CHIVO() Returns the CHIVO enum instance
 * @method static CHONBURI() Returns the CHONBURI enum instance
 * @method static CINZEL() Returns the CINZEL enum instance
 * @method static CINZEL_DECORATIVE() Returns the CINZEL_DECORATIVE enum instance
 * @method static CLICKER_SCRIPT() Returns the CLICKER_SCRIPT enum instance
 * @method static CODA() Returns the CODA enum instance
 * @method static CODA_CAPTION() Returns the CODA_CAPTION enum instance
 * @method static CODYSTAR() Returns the CODYSTAR enum instance
 * @method static COINY() Returns the COINY enum instance
 * @method static COMBO() Returns the COMBO enum instance
 * @method static COMFORTAA() Returns the COMFORTAA enum instance
 * @method static COMING_SOON() Returns the COMING_SOON enum instance
 * @method static CONCERT_ONE() Returns the CONCERT_ONE enum instance
 * @method static CONDIMENT() Returns the CONDIMENT enum instance
 * @method static CONTENT() Returns the CONTENT enum instance
 * @method static CONTRAIL_ONE() Returns the CONTRAIL_ONE enum instance
 * @method static CONVERGENCE() Returns the CONVERGENCE enum instance
 * @method static COOKIE() Returns the COOKIE enum instance
 * @method static COPSE() Returns the COPSE enum instance
 * @method static CORBEN() Returns the CORBEN enum instance
 * @method static CORMORANT() Returns the CORMORANT enum instance
 * @method static CORMORANT_GARAMOND() Returns the CORMORANT_GARAMOND enum instance
 * @method static CORMORANT_INFANT() Returns the CORMORANT_INFANT enum instance
 * @method static CORMORANT_SC() Returns the CORMORANT_SC enum instance
 * @method static CORMORANT_UNICASE() Returns the CORMORANT_UNICASE enum instance
 * @method static CORMORANT_UPRIGHT() Returns the CORMORANT_UPRIGHT enum instance
 * @method static COURGETTE() Returns the COURGETTE enum instance
 * @method static COUSINE() Returns the COUSINE enum instance
 * @method static COUSTARD() Returns the COUSTARD enum instance
 * @method static COVERED_BY_YOUR_GRACE() Returns the COVERED_BY_YOUR_GRACE enum instance
 * @method static CRAFTY_GIRLS() Returns the CRAFTY_GIRLS enum instance
 * @method static CREEPSTER() Returns the CREEPSTER enum instance
 * @method static CRETE_ROUND() Returns the CRETE_ROUND enum instance
 * @method static CRIMSON_TEXT() Returns the CRIMSON_TEXT enum instance
 * @method static CROISSANT_ONE() Returns the CROISSANT_ONE enum instance
 * @method static CRUSHED() Returns the CRUSHED enum instance
 * @method static CUPRUM() Returns the CUPRUM enum instance
 * @method static CUTIVE() Returns the CUTIVE enum instance
 * @method static CUTIVE_MONO() Returns the CUTIVE_MONO enum instance
 * @method static DAMION() Returns the DAMION enum instance
 * @method static DANCING_SCRIPT() Returns the DANCING_SCRIPT enum instance
 * @method static DANGREK() Returns the DANGREK enum instance
 * @method static DAVID_LIBRE() Returns the DAVID_LIBRE enum instance
 * @method static DAWNING_OF_A_NEW() Returns the DAWNING_OF_A_NEW enum instance
 * @method static DAYS_ONE() Returns the DAYS_ONE enum instance
 * @method static DEKKO() Returns the DEKKO enum instance
 * @method static DELIUS() Returns the DELIUS enum instance
 * @method static DELIUS_SWASH_CAPS() Returns the DELIUS_SWASH_CAPS enum instance
 * @method static DELIUS_UNICASE() Returns the DELIUS_UNICASE enum instance
 * @method static DELLA_RESPIRA() Returns the DELLA_RESPIRA enum instance
 * @method static DENK_ONE() Returns the DENK_ONE enum instance
 * @method static DEVONSHIRE() Returns the DEVONSHIRE enum instance
 * @method static DHURJATI() Returns the DHURJATI enum instance
 * @method static DIDACT_GOTHIC() Returns the DIDACT_GOTHIC enum instance
 * @method static DIPLOMATA() Returns the DIPLOMATA enum instance
 * @method static DIPLOMATA_SC() Returns the DIPLOMATA_SC enum instance
 * @method static DOMINE() Returns the DOMINE enum instance
 * @method static DONEGAL_ONE() Returns the DONEGAL_ONE enum instance
 * @method static DOPPIO_ONE() Returns the DOPPIO_ONE enum instance
 * @method static DORSA() Returns the DORSA enum instance
 * @method static DOSIS() Returns the DOSIS enum instance
 * @method static DR_SUGIYAMA() Returns the DR_SUGIYAMA enum instance
 * @method static DURU_SANS() Returns the DURU_SANS enum instance
 * @method static DYNALIGHT() Returns the DYNALIGHT enum instance
 * @method static EB_GARAMOND() Returns the EB_GARAMOND enum instance
 * @method static EAGLE_LAKE() Returns the EAGLE_LAKE enum instance
 * @method static EATER() Returns the EATER enum instance
 * @method static ECONOMICA() Returns the ECONOMICA enum instance
 * @method static ECZAR() Returns the ECZAR enum instance
 * @method static EL_MESSIRI() Returns the EL_MESSIRI enum instance
 * @method static ELECTROLIZE() Returns the ELECTROLIZE enum instance
 * @method static ELSIE() Returns the ELSIE enum instance
 * @method static ELSIE_SWASH_CAPS() Returns the ELSIE_SWASH_CAPS enum instance
 * @method static EMBLEMA_ONE() Returns the EMBLEMA_ONE enum instance
 * @method static EMILYS_CANDY() Returns the EMILYS_CANDY enum instance
 * @method static ENCODE_SANS() Returns the ENCODE_SANS enum instance
 * @method static ENCODE_SANS_CONDENSED() Returns the ENCODE_SANS_CONDENSED enum instance
 * @method static ENCODE_SANS_EXPANDED() Returns the ENCODE_SANS_EXPANDED enum instance
 * @method static ENCODE_SANS_SEMI_CONDENSED() Returns the ENCODE_SANS_SEMI_CONDENSED enum instance
 * @method static ENCODE_SANS_SEMI_EXPANDED() Returns the ENCODE_SANS_SEMI_EXPANDED enum instance
 * @method static ENGAGEMENT() Returns the ENGAGEMENT enum instance
 * @method static ENGLEBERT() Returns the ENGLEBERT enum instance
 * @method static ENRIQUETA() Returns the ENRIQUETA enum instance
 * @method static ERICA_ONE() Returns the ERICA_ONE enum instance
 * @method static ESTEBAN() Returns the ESTEBAN enum instance
 * @method static EUPHORIA_SCRIPT() Returns the EUPHORIA_SCRIPT enum instance
 * @method static EWERT() Returns the EWERT enum instance
 * @method static EXO() Returns the EXO enum instance
 * @method static EXO_2() Returns the EXO_2 enum instance
 * @method static EXPLETUS_SANS() Returns the EXPLETUS_SANS enum instance
 * @method static FANWOOD_TEXT() Returns the FANWOOD_TEXT enum instance
 * @method static FARSAN() Returns the FARSAN enum instance
 * @method static FASCINATE() Returns the FASCINATE enum instance
 * @method static FASCINATE_INLINE() Returns the FASCINATE_INLINE enum instance
 * @method static FASTER_ONE() Returns the FASTER_ONE enum instance
 * @method static FASTHAND() Returns the FASTHAND enum instance
 * @method static FAUNA_ONE() Returns the FAUNA_ONE enum instance
 * @method static FAUSTINA() Returns the FAUSTINA enum instance
 * @method static FEDERANT() Returns the FEDERANT enum instance
 * @method static FEDERO() Returns the FEDERO enum instance
 * @method static FELIPA() Returns the FELIPA enum instance
 * @method static FENIX() Returns the FENIX enum instance
 * @method static FINGER_PAINT() Returns the FINGER_PAINT enum instance
 * @method static FIRA_MONO() Returns the FIRA_MONO enum instance
 * @method static FIRA_SANS() Returns the FIRA_SANS enum instance
 * @method static FIRA_SANS_CONDENSED() Returns the FIRA_SANS_CONDENSED enum instance
 * @method static FIRA_SANS_EXTRA_CONDENSED() Returns the FIRA_SANS_EXTRA_CONDENSED enum instance
 * @method static FJALLA_ONE() Returns the FJALLA_ONE enum instance
 * @method static FJORD_ONE() Returns the FJORD_ONE enum instance
 * @method static FLAMENCO() Returns the FLAMENCO enum instance
 * @method static FLAVORS() Returns the FLAVORS enum instance
 * @method static FONDAMENTO() Returns the FONDAMENTO enum instance
 * @method static FONTDINER_SWANKY() Returns the FONTDINER_SWANKY enum instance
 * @method static FORUM() Returns the FORUM enum instance
 * @method static FRANCOIS_ONE() Returns the FRANCOIS_ONE enum instance
 * @method static FRANK_RUHL_LIBRE() Returns the FRANK_RUHL_LIBRE enum instance
 * @method static FRECKLE_FACE() Returns the FRECKLE_FACE enum instance
 * @method static FREDERICKA_THE_GREAT() Returns the FREDERICKA_THE_GREAT enum instance
 * @method static FREDOKA_ONE() Returns the FREDOKA_ONE enum instance
 * @method static FREEHAND() Returns the FREEHAND enum instance
 * @method static FRESCA() Returns the FRESCA enum instance
 * @method static FRIJOLE() Returns the FRIJOLE enum instance
 * @method static FRUKTUR() Returns the FRUKTUR enum instance
 * @method static FUGAZ_ONE() Returns the FUGAZ_ONE enum instance
 * @method static GFS_DIDOT() Returns the GFS_DIDOT enum instance
 * @method static GFS_NEOHELLENIC() Returns the GFS_NEOHELLENIC enum instance
 * @method static GABRIELA() Returns the GABRIELA enum instance
 * @method static GAFATA() Returns the GAFATA enum instance
 * @method static GALADA() Returns the GALADA enum instance
 * @method static GALDEANO() Returns the GALDEANO enum instance
 * @method static GALINDO() Returns the GALINDO enum instance
 * @method static GENTIUM_BASIC() Returns the GENTIUM_BASIC enum instance
 * @method static GENTIUM_BOOK_BASIC() Returns the GENTIUM_BOOK_BASIC enum instance
 * @method static GEO() Returns the GEO enum instance
 * @method static GEOSTAR() Returns the GEOSTAR enum instance
 * @method static GEOSTAR_FILL() Returns the GEOSTAR_FILL enum instance
 * @method static GERMANIA_ONE() Returns the GERMANIA_ONE enum instance
 * @method static GIDUGU() Returns the GIDUGU enum instance
 * @method static GILDA_DISPLAY() Returns the GILDA_DISPLAY enum instance
 * @method static GIVE_YOU_GLORY() Returns the GIVE_YOU_GLORY enum instance
 * @method static GLASS_ANTIQUA() Returns the GLASS_ANTIQUA enum instance
 * @method static GLEGOO() Returns the GLEGOO enum instance
 * @method static GLORIA_HALLELUJAH() Returns the GLORIA_HALLELUJAH enum instance
 * @method static GOBLIN_ONE() Returns the GOBLIN_ONE enum instance
 * @method static GOCHI_HAND() Returns the GOCHI_HAND enum instance
 * @method static GORDITAS() Returns the GORDITAS enum instance
 * @method static GOUDY_BOOKLETTER_1911() Returns the GOUDY_BOOKLETTER_1911 enum instance
 * @method static GRADUATE() Returns the GRADUATE enum instance
 * @method static GRAND_HOTEL() Returns the GRAND_HOTEL enum instance
 * @method static GRAVITAS_ONE() Returns the GRAVITAS_ONE enum instance
 * @method static GREAT_VIBES() Returns the GREAT_VIBES enum instance
 * @method static GRIFFY() Returns the GRIFFY enum instance
 * @method static GRUPPO() Returns the GRUPPO enum instance
 * @method static GUDEA() Returns the GUDEA enum instance
 * @method static GURAJADA() Returns the GURAJADA enum instance
 * @method static HABIBI() Returns the HABIBI enum instance
 * @method static HALANT() Returns the HALANT enum instance
 * @method static HAMMERSMITH_ONE() Returns the HAMMERSMITH_ONE enum instance
 * @method static HANALEI() Returns the HANALEI enum instance
 * @method static HANALEI_FILL() Returns the HANALEI_FILL enum instance
 * @method static HANDLEE() Returns the HANDLEE enum instance
 * @method static HANUMAN() Returns the HANUMAN enum instance
 * @method static HAPPY_MONKEY() Returns the HAPPY_MONKEY enum instance
 * @method static HARMATTAN() Returns the HARMATTAN enum instance
 * @method static HEADLAND_ONE() Returns the HEADLAND_ONE enum instance
 * @method static HEEBO() Returns the HEEBO enum instance
 * @method static HENNY_PENNY() Returns the HENNY_PENNY enum instance
 * @method static HERR_VON_MUELLERHOFF() Returns the HERR_VON_MUELLERHOFF enum instance
 * @method static HIND() Returns the HIND enum instance
 * @method static HIND_GUNTUR() Returns the HIND_GUNTUR enum instance
 * @method static HIND_MADURAI() Returns the HIND_MADURAI enum instance
 * @method static HIND_SILIGURI() Returns the HIND_SILIGURI enum instance
 * @method static HIND_VADODARA() Returns the HIND_VADODARA enum instance
 * @method static HOLTWOOD_ONE_SC() Returns the HOLTWOOD_ONE_SC enum instance
 * @method static HOMEMADE_APPLE() Returns the HOMEMADE_APPLE enum instance
 * @method static HOMENAJE() Returns the HOMENAJE enum instance
 * @method static IM_FELL_DW_PICA() Returns the IM_FELL_DW_PICA enum instance
 * @method static IM_FELL_DOUBLE_PICA() Returns the IM_FELL_DOUBLE_PICA enum instance
 * @method static IM_FELL_ENGLISH() Returns the IM_FELL_ENGLISH enum instance
 * @method static IM_FELL_ENGLISH_SC() Returns the IM_FELL_ENGLISH_SC enum instance
 * @method static IM_FELL_FRENCH_CANON() Returns the IM_FELL_FRENCH_CANON enum instance
 * @method static IM_FELL_GREAT_PRIMER() Returns the IM_FELL_GREAT_PRIMER enum instance
 * @method static ICEBERG() Returns the ICEBERG enum instance
 * @method static ICELAND() Returns the ICELAND enum instance
 * @method static IMPRIMA() Returns the IMPRIMA enum instance
 * @method static INCONSOLATA() Returns the INCONSOLATA enum instance
 * @method static INDER() Returns the INDER enum instance
 * @method static INDIE_FLOWER() Returns the INDIE_FLOWER enum instance
 * @method static INIKA() Returns the INIKA enum instance
 * @method static INKNUT_ANTIQUA() Returns the INKNUT_ANTIQUA enum instance
 * @method static IRISH_GROVER() Returns the IRISH_GROVER enum instance
 * @method static ISTOK_WEB() Returns the ISTOK_WEB enum instance
 * @method static ITALIANA() Returns the ITALIANA enum instance
 * @method static ITALIANNO() Returns the ITALIANNO enum instance
 * @method static ITIM() Returns the ITIM enum instance
 * @method static JACQUES_FRANCOIS() Returns the JACQUES_FRANCOIS enum instance
 * @method static JACQUES_FRANCOIS_SHADOW() Returns the JACQUES_FRANCOIS_SHADOW enum instance
 * @method static JALDI() Returns the JALDI enum instance
 * @method static JIM_NIGHTSHADE() Returns the JIM_NIGHTSHADE enum instance
 * @method static JOCKEY_ONE() Returns the JOCKEY_ONE enum instance
 * @method static JOLLY_LODGER() Returns the JOLLY_LODGER enum instance
 * @method static JOMHURIA() Returns the JOMHURIA enum instance
 * @method static JOSEFIN_SANS() Returns the JOSEFIN_SANS enum instance
 * @method static JOSEFIN_SLAB() Returns the JOSEFIN_SLAB enum instance
 * @method static JOTI_ONE() Returns the JOTI_ONE enum instance
 * @method static JUDSON() Returns the JUDSON enum instance
 * @method static JULEE() Returns the JULEE enum instance
 * @method static JULIUS_SANS_ONE() Returns the JULIUS_SANS_ONE enum instance
 * @method static JUNGE() Returns the JUNGE enum instance
 * @method static JURA() Returns the JURA enum instance
 * @method static JUST_ANOTHER_HAND() Returns the JUST_ANOTHER_HAND enum instance
 * @method static JUST_ME_AGAIN_DOWN() Returns the JUST_ME_AGAIN_DOWN enum instance
 * @method static KADWA() Returns the KADWA enum instance
 * @method static KALAM() Returns the KALAM enum instance
 * @method static KAMERON() Returns the KAMERON enum instance
 * @method static KANIT() Returns the KANIT enum instance
 * @method static KANTUMRUY() Returns the KANTUMRUY enum instance
 * @method static KARLA() Returns the KARLA enum instance
 * @method static KARMA() Returns the KARMA enum instance
 * @method static KATIBEH() Returns the KATIBEH enum instance
 * @method static KAUSHAN_SCRIPT() Returns the KAUSHAN_SCRIPT enum instance
 * @method static KAVIVANAR() Returns the KAVIVANAR enum instance
 * @method static KAVOON() Returns the KAVOON enum instance
 * @method static KDAM_THMOR() Returns the KDAM_THMOR enum instance
 * @method static KEANIA_ONE() Returns the KEANIA_ONE enum instance
 * @method static KELLY_SLAB() Returns the KELLY_SLAB enum instance
 * @method static KENIA() Returns the KENIA enum instance
 * @method static KHAND() Returns the KHAND enum instance
 * @method static KHMER() Returns the KHMER enum instance
 * @method static KHULA() Returns the KHULA enum instance
 * @method static KITE_ONE() Returns the KITE_ONE enum instance
 * @method static KNEWAVE() Returns the KNEWAVE enum instance
 * @method static KOTTA_ONE() Returns the KOTTA_ONE enum instance
 * @method static KOULEN() Returns the KOULEN enum instance
 * @method static KRANKY() Returns the KRANKY enum instance
 * @method static KREON() Returns the KREON enum instance
 * @method static KRISTI() Returns the KRISTI enum instance
 * @method static KRONA_ONE() Returns the KRONA_ONE enum instance
 * @method static KUMAR_ONE() Returns the KUMAR_ONE enum instance
 * @method static KUMAR_ONE_OUTLINE() Returns the KUMAR_ONE_OUTLINE enum instance
 * @method static KURALE() Returns the KURALE enum instance
 * @method static LA_BELLE_AURORE() Returns the LA_BELLE_AURORE enum instance
 * @method static LAILA() Returns the LAILA enum instance
 * @method static LAKKI_REDDY() Returns the LAKKI_REDDY enum instance
 * @method static LALEZAR() Returns the LALEZAR enum instance
 * @method static LANCELOT() Returns the LANCELOT enum instance
 * @method static LATEEF() Returns the LATEEF enum instance
 * @method static LATO() Returns the LATO enum instance
 * @method static LEAGUE_SCRIPT() Returns the LEAGUE_SCRIPT enum instance
 * @method static LECKERLI_ONE() Returns the LECKERLI_ONE enum instance
 * @method static LEDGER() Returns the LEDGER enum instance
 * @method static LEKTON() Returns the LEKTON enum instance
 * @method static LEMON() Returns the LEMON enum instance
 * @method static LEMONADA() Returns the LEMONADA enum instance
 * @method static LIBRE_BARCODE_128() Returns the LIBRE_BARCODE_128 enum instance
 * @method static LIBRE_BARCODE_128_TEXT() Returns the LIBRE_BARCODE_128_TEXT enum instance
 * @method static LIBRE_BARCODE_39() Returns the LIBRE_BARCODE_39 enum instance
 * @method static LIBRE_BARCODE_39_EXTENDED() Returns the LIBRE_BARCODE_39_EXTENDED enum instance
 * @method static LIBRE_BARCODE_39_TEXT() Returns the LIBRE_BARCODE_39_TEXT enum instance
 * @method static LIBRE_BASKERVILLE() Returns the LIBRE_BASKERVILLE enum instance
 * @method static LIBRE_FRANKLIN() Returns the LIBRE_FRANKLIN enum instance
 * @method static LIFE_SAVERS() Returns the LIFE_SAVERS enum instance
 * @method static LILITA_ONE() Returns the LILITA_ONE enum instance
 * @method static LILY_SCRIPT_ONE() Returns the LILY_SCRIPT_ONE enum instance
 * @method static LIMELIGHT() Returns the LIMELIGHT enum instance
 * @method static LINDEN_HILL() Returns the LINDEN_HILL enum instance
 * @method static LOBSTER() Returns the LOBSTER enum instance
 * @method static LOBSTER_TWO() Returns the LOBSTER_TWO enum instance
 * @method static LONDRINA_OUTLINE() Returns the LONDRINA_OUTLINE enum instance
 * @method static LONDRINA_SHADOW() Returns the LONDRINA_SHADOW enum instance
 * @method static LONDRINA_SKETCH() Returns the LONDRINA_SKETCH enum instance
 * @method static LONDRINA_SOLID() Returns the LONDRINA_SOLID enum instance
 * @method static LORA() Returns the LORA enum instance
 * @method static LOVE_YA_LIKE_A() Returns the LOVE_YA_LIKE_A enum instance
 * @method static LOVED_BY_THE_KING() Returns the LOVED_BY_THE_KING enum instance
 * @method static LOVERS_QUARREL() Returns the LOVERS_QUARREL enum instance
 * @method static LUCKIEST_GUY() Returns the LUCKIEST_GUY enum instance
 * @method static LUSITANA() Returns the LUSITANA enum instance
 * @method static LUSTRIA() Returns the LUSTRIA enum instance
 * @method static MACONDO() Returns the MACONDO enum instance
 * @method static MACONDO_SWASH_CAPS() Returns the MACONDO_SWASH_CAPS enum instance
 * @method static MADA() Returns the MADA enum instance
 * @method static MAGRA() Returns the MAGRA enum instance
 * @method static MAIDEN_ORANGE() Returns the MAIDEN_ORANGE enum instance
 * @method static MAITREE() Returns the MAITREE enum instance
 * @method static MAKO() Returns the MAKO enum instance
 * @method static MALLANNA() Returns the MALLANNA enum instance
 * @method static MANDALI() Returns the MANDALI enum instance
 * @method static MANUALE() Returns the MANUALE enum instance
 * @method static MARCELLUS() Returns the MARCELLUS enum instance
 * @method static MARCELLUS_SC() Returns the MARCELLUS_SC enum instance
 * @method static MARCK_SCRIPT() Returns the MARCK_SCRIPT enum instance
 * @method static MARGARINE() Returns the MARGARINE enum instance
 * @method static MARKO_ONE() Returns the MARKO_ONE enum instance
 * @method static MARMELAD() Returns the MARMELAD enum instance
 * @method static MARTEL() Returns the MARTEL enum instance
 * @method static MARTEL_SANS() Returns the MARTEL_SANS enum instance
 * @method static MARVEL() Returns the MARVEL enum instance
 * @method static MATE() Returns the MATE enum instance
 * @method static MATE_SC() Returns the MATE_SC enum instance
 * @method static MAVEN_PRO() Returns the MAVEN_PRO enum instance
 * @method static MCLAREN() Returns the MCLAREN enum instance
 * @method static MEDDON() Returns the MEDDON enum instance
 * @method static MEDIEVALSHARP() Returns the MEDIEVALSHARP enum instance
 * @method static MEDULA_ONE() Returns the MEDULA_ONE enum instance
 * @method static MEERA_INIMAI() Returns the MEERA_INIMAI enum instance
 * @method static MEGRIM() Returns the MEGRIM enum instance
 * @method static MEIE_SCRIPT() Returns the MEIE_SCRIPT enum instance
 * @method static MERIENDA() Returns the MERIENDA enum instance
 * @method static MERIENDA_ONE() Returns the MERIENDA_ONE enum instance
 * @method static MERRIWEATHER() Returns the MERRIWEATHER enum instance
 * @method static MERRIWEATHER_SANS() Returns the MERRIWEATHER_SANS enum instance
 * @method static METAL() Returns the METAL enum instance
 * @method static METAL_MANIA() Returns the METAL_MANIA enum instance
 * @method static METAMORPHOUS() Returns the METAMORPHOUS enum instance
 * @method static METROPHOBIC() Returns the METROPHOBIC enum instance
 * @method static MICHROMA() Returns the MICHROMA enum instance
 * @method static MILONGA() Returns the MILONGA enum instance
 * @method static MILTONIAN() Returns the MILTONIAN enum instance
 * @method static MILTONIAN_TATTOO() Returns the MILTONIAN_TATTOO enum instance
 * @method static MINIVER() Returns the MINIVER enum instance
 * @method static MIRIAM_LIBRE() Returns the MIRIAM_LIBRE enum instance
 * @method static MIRZA() Returns the MIRZA enum instance
 * @method static MISS_FAJARDOSE() Returns the MISS_FAJARDOSE enum instance
 * @method static MITR() Returns the MITR enum instance
 * @method static MODAK() Returns the MODAK enum instance
 * @method static MODERN_ANTIQUA() Returns the MODERN_ANTIQUA enum instance
 * @method static MOGRA() Returns the MOGRA enum instance
 * @method static MOLENGO() Returns the MOLENGO enum instance
 * @method static MOLLE() Returns the MOLLE enum instance
 * @method static MONDA() Returns the MONDA enum instance
 * @method static MONOFETT() Returns the MONOFETT enum instance
 * @method static MONOTON() Returns the MONOTON enum instance
 * @method static MONSIEUR_LA_DOULAISE() Returns the MONSIEUR_LA_DOULAISE enum instance
 * @method static MONTAGA() Returns the MONTAGA enum instance
 * @method static MONTEZ() Returns the MONTEZ enum instance
 * @method static MONTSERRAT() Returns the MONTSERRAT enum instance
 * @method static MONTSERRAT_ALTERNATES() Returns the MONTSERRAT_ALTERNATES enum instance
 * @method static MONTSERRAT_SUBRAYADA() Returns the MONTSERRAT_SUBRAYADA enum instance
 * @method static MOUL() Returns the MOUL enum instance
 * @method static MOULPALI() Returns the MOULPALI enum instance
 * @method static MOUNTAINS_OF_CHRISTMAS() Returns the MOUNTAINS_OF_CHRISTMAS enum instance
 * @method static MOUSE_MEMOIRS() Returns the MOUSE_MEMOIRS enum instance
 * @method static MR_BEDFORT() Returns the MR_BEDFORT enum instance
 * @method static MR_DAFOE() Returns the MR_DAFOE enum instance
 * @method static MR_DE_HAVILAND() Returns the MR_DE_HAVILAND enum instance
 * @method static MRS_SAINT_DELAFIELD() Returns the MRS_SAINT_DELAFIELD enum instance
 * @method static MRS_SHEPPARDS() Returns the MRS_SHEPPARDS enum instance
 * @method static MUKTA() Returns the MUKTA enum instance
 * @method static MUKTA_MAHEE() Returns the MUKTA_MAHEE enum instance
 * @method static MUKTA_MALAR() Returns the MUKTA_MALAR enum instance
 * @method static MUKTA_VAANI() Returns the MUKTA_VAANI enum instance
 * @method static MULI() Returns the MULI enum instance
 * @method static MYSTERY_QUEST() Returns the MYSTERY_QUEST enum instance
 * @method static NTR() Returns the NTR enum instance
 * @method static NEUCHA() Returns the NEUCHA enum instance
 * @method static NEUTON() Returns the NEUTON enum instance
 * @method static NEW_ROCKER() Returns the NEW_ROCKER enum instance
 * @method static NEWS_CYCLE() Returns the NEWS_CYCLE enum instance
 * @method static NICONNE() Returns the NICONNE enum instance
 * @method static NIXIE_ONE() Returns the NIXIE_ONE enum instance
 * @method static NOBILE() Returns the NOBILE enum instance
 * @method static NOKORA() Returns the NOKORA enum instance
 * @method static NORICAN() Returns the NORICAN enum instance
 * @method static NOSIFER() Returns the NOSIFER enum instance
 * @method static NOTHING_YOU_COULD_DO() Returns the NOTHING_YOU_COULD_DO enum instance
 * @method static NOTICIA_TEXT() Returns the NOTICIA_TEXT enum instance
 * @method static NOTO_SANS() Returns the NOTO_SANS enum instance
 * @method static NOTO_SERIF() Returns the NOTO_SERIF enum instance
 * @method static NOVA_CUT() Returns the NOVA_CUT enum instance
 * @method static NOVA_FLAT() Returns the NOVA_FLAT enum instance
 * @method static NOVA_MONO() Returns the NOVA_MONO enum instance
 * @method static NOVA_OVAL() Returns the NOVA_OVAL enum instance
 * @method static NOVA_ROUND() Returns the NOVA_ROUND enum instance
 * @method static NOVA_SCRIPT() Returns the NOVA_SCRIPT enum instance
 * @method static NOVA_SLIM() Returns the NOVA_SLIM enum instance
 * @method static NOVA_SQUARE() Returns the NOVA_SQUARE enum instance
 * @method static NUMANS() Returns the NUMANS enum instance
 * @method static NUNITO() Returns the NUNITO enum instance
 * @method static NUNITO_SANS() Returns the NUNITO_SANS enum instance
 * @method static ODOR_MEAN_CHEY() Returns the ODOR_MEAN_CHEY enum instance
 * @method static OFFSIDE() Returns the OFFSIDE enum instance
 * @method static OLD_STANDARD_TT() Returns the OLD_STANDARD_TT enum instance
 * @method static OLDENBURG() Returns the OLDENBURG enum instance
 * @method static OLEO_SCRIPT() Returns the OLEO_SCRIPT enum instance
 * @method static OLEO_SCRIPT_SWASH_CAPS() Returns the OLEO_SCRIPT_SWASH_CAPS enum instance
 * @method static OPEN_SANS() Returns the OPEN_SANS enum instance
 * @method static OPEN_SANS_CONDENSED() Returns the OPEN_SANS_CONDENSED enum instance
 * @method static ORANIENBAUM() Returns the ORANIENBAUM enum instance
 * @method static ORBITRON() Returns the ORBITRON enum instance
 * @method static OREGANO() Returns the OREGANO enum instance
 * @method static ORIENTA() Returns the ORIENTA enum instance
 * @method static ORIGINAL_SURFER() Returns the ORIGINAL_SURFER enum instance
 * @method static OSWALD() Returns the OSWALD enum instance
 * @method static OVER_THE_RAINBOW() Returns the OVER_THE_RAINBOW enum instance
 * @method static OVERLOCK() Returns the OVERLOCK enum instance
 * @method static OVERLOCK_SC() Returns the OVERLOCK_SC enum instance
 * @method static OVERPASS() Returns the OVERPASS enum instance
 * @method static OVERPASS_MONO() Returns the OVERPASS_MONO enum instance
 * @method static OVO() Returns the OVO enum instance
 * @method static OXYGEN() Returns the OXYGEN enum instance
 * @method static OXYGEN_MONO() Returns the OXYGEN_MONO enum instance
 * @method static PT_MONO() Returns the PT_MONO enum instance
 * @method static PT_SANS() Returns the PT_SANS enum instance
 * @method static PT_SANS_CAPTION() Returns the PT_SANS_CAPTION enum instance
 * @method static PT_SANS_NARROW() Returns the PT_SANS_NARROW enum instance
 * @method static PT_SERIF() Returns the PT_SERIF enum instance
 * @method static PT_SERIF_CAPTION() Returns the PT_SERIF_CAPTION enum instance
 * @method static PACIFICO() Returns the PACIFICO enum instance
 * @method static PADAUK() Returns the PADAUK enum instance
 * @method static PALANQUIN() Returns the PALANQUIN enum instance
 * @method static PALANQUIN_DARK() Returns the PALANQUIN_DARK enum instance
 * @method static PANGOLIN() Returns the PANGOLIN enum instance
 * @method static PAPRIKA() Returns the PAPRIKA enum instance
 * @method static PARISIENNE() Returns the PARISIENNE enum instance
 * @method static PASSERO_ONE() Returns the PASSERO_ONE enum instance
 * @method static PASSION_ONE() Returns the PASSION_ONE enum instance
 * @method static PATHWAY_GOTHIC_ONE() Returns the PATHWAY_GOTHIC_ONE enum instance
 * @method static PATRICK_HAND() Returns the PATRICK_HAND enum instance
 * @method static PATRICK_HAND_SC() Returns the PATRICK_HAND_SC enum instance
 * @method static PATTAYA() Returns the PATTAYA enum instance
 * @method static PATUA_ONE() Returns the PATUA_ONE enum instance
 * @method static PAVANAM() Returns the PAVANAM enum instance
 * @method static PAYTONE_ONE() Returns the PAYTONE_ONE enum instance
 * @method static PEDDANA() Returns the PEDDANA enum instance
 * @method static PERALTA() Returns the PERALTA enum instance
 * @method static PERMANENT_MARKER() Returns the PERMANENT_MARKER enum instance
 * @method static PETIT_FORMAL_SCRIPT() Returns the PETIT_FORMAL_SCRIPT enum instance
 * @method static PETRONA() Returns the PETRONA enum instance
 * @method static PHILOSOPHER() Returns the PHILOSOPHER enum instance
 * @method static PIEDRA() Returns the PIEDRA enum instance
 * @method static PINYON_SCRIPT() Returns the PINYON_SCRIPT enum instance
 * @method static PIRATA_ONE() Returns the PIRATA_ONE enum instance
 * @method static PLASTER() Returns the PLASTER enum instance
 * @method static PLAY() Returns the PLAY enum instance
 * @method static PLAYBALL() Returns the PLAYBALL enum instance
 * @method static PLAYFAIR_DISPLAY() Returns the PLAYFAIR_DISPLAY enum instance
 * @method static PLAYFAIR_DISPLAY_SC() Returns the PLAYFAIR_DISPLAY_SC enum instance
 * @method static PODKOVA() Returns the PODKOVA enum instance
 * @method static POIRET_ONE() Returns the POIRET_ONE enum instance
 * @method static POLLER_ONE() Returns the POLLER_ONE enum instance
 * @method static POLY() Returns the POLY enum instance
 * @method static POMPIERE() Returns the POMPIERE enum instance
 * @method static PONTANO_SANS() Returns the PONTANO_SANS enum instance
 * @method static POPPINS() Returns the POPPINS enum instance
 * @method static PORT_LLIGAT_SANS() Returns the PORT_LLIGAT_SANS enum instance
 * @method static PORT_LLIGAT_SLAB() Returns the PORT_LLIGAT_SLAB enum instance
 * @method static PRAGATI_NARROW() Returns the PRAGATI_NARROW enum instance
 * @method static PRATA() Returns the PRATA enum instance
 * @method static PREAHVIHEAR() Returns the PREAHVIHEAR enum instance
 * @method static PRESS_START_2P() Returns the PRESS_START_2P enum instance
 * @method static PRIDI() Returns the PRIDI enum instance
 * @method static PRINCESS_SOFIA() Returns the PRINCESS_SOFIA enum instance
 * @method static PROCIONO() Returns the PROCIONO enum instance
 * @method static PROMPT() Returns the PROMPT enum instance
 * @method static PROSTO_ONE() Returns the PROSTO_ONE enum instance
 * @method static PROZA_LIBRE() Returns the PROZA_LIBRE enum instance
 * @method static PURITAN() Returns the PURITAN enum instance
 * @method static PURPLE_PURSE() Returns the PURPLE_PURSE enum instance
 * @method static QUANDO() Returns the QUANDO enum instance
 * @method static QUANTICO() Returns the QUANTICO enum instance
 * @method static QUATTROCENTO() Returns the QUATTROCENTO enum instance
 * @method static QUATTROCENTO_SANS() Returns the QUATTROCENTO_SANS enum instance
 * @method static QUESTRIAL() Returns the QUESTRIAL enum instance
 * @method static QUICKSAND() Returns the QUICKSAND enum instance
 * @method static QUINTESSENTIAL() Returns the QUINTESSENTIAL enum instance
 * @method static QWIGLEY() Returns the QWIGLEY enum instance
 * @method static RACING_SANS_ONE() Returns the RACING_SANS_ONE enum instance
 * @method static RADLEY() Returns the RADLEY enum instance
 * @method static RAJDHANI() Returns the RAJDHANI enum instance
 * @method static RAKKAS() Returns the RAKKAS enum instance
 * @method static RALEWAY() Returns the RALEWAY enum instance
 * @method static RALEWAY_DOTS() Returns the RALEWAY_DOTS enum instance
 * @method static RAMABHADRA() Returns the RAMABHADRA enum instance
 * @method static RAMARAJA() Returns the RAMARAJA enum instance
 * @method static RAMBLA() Returns the RAMBLA enum instance
 * @method static RAMMETTO_ONE() Returns the RAMMETTO_ONE enum instance
 * @method static RANCHERS() Returns the RANCHERS enum instance
 * @method static RANCHO() Returns the RANCHO enum instance
 * @method static RANGA() Returns the RANGA enum instance
 * @method static RASA() Returns the RASA enum instance
 * @method static RATIONALE() Returns the RATIONALE enum instance
 * @method static RAVI_PRAKASH() Returns the RAVI_PRAKASH enum instance
 * @method static REDRESSED() Returns the REDRESSED enum instance
 * @method static REEM_KUFI() Returns the REEM_KUFI enum instance
 * @method static REENIE_BEANIE() Returns the REENIE_BEANIE enum instance
 * @method static REVALIA() Returns the REVALIA enum instance
 * @method static RHODIUM_LIBRE() Returns the RHODIUM_LIBRE enum instance
 * @method static RIBEYE() Returns the RIBEYE enum instance
 * @method static RIBEYE_MARROW() Returns the RIBEYE_MARROW enum instance
 * @method static RIGHTEOUS() Returns the RIGHTEOUS enum instance
 * @method static RISQUE() Returns the RISQUE enum instance
 * @method static ROBOTO() Returns the ROBOTO enum instance
 * @method static ROBOTO_CONDENSED() Returns the ROBOTO_CONDENSED enum instance
 * @method static ROBOTO_MONO() Returns the ROBOTO_MONO enum instance
 * @method static ROBOTO_SLAB() Returns the ROBOTO_SLAB enum instance
 * @method static ROCHESTER() Returns the ROCHESTER enum instance
 * @method static ROCK_SALT() Returns the ROCK_SALT enum instance
 * @method static ROKKITT() Returns the ROKKITT enum instance
 * @method static ROMANESCO() Returns the ROMANESCO enum instance
 * @method static ROPA_SANS() Returns the ROPA_SANS enum instance
 * @method static ROSARIO() Returns the ROSARIO enum instance
 * @method static ROSARIVO() Returns the ROSARIVO enum instance
 * @method static ROUGE_SCRIPT() Returns the ROUGE_SCRIPT enum instance
 * @method static ROZHA_ONE() Returns the ROZHA_ONE enum instance
 * @method static RUBIK() Returns the RUBIK enum instance
 * @method static RUBIK_MONO_ONE() Returns the RUBIK_MONO_ONE enum instance
 * @method static RUDA() Returns the RUDA enum instance
 * @method static RUFINA() Returns the RUFINA enum instance
 * @method static RUGE_BOOGIE() Returns the RUGE_BOOGIE enum instance
 * @method static RULUKO() Returns the RULUKO enum instance
 * @method static RUM_RAISIN() Returns the RUM_RAISIN enum instance
 * @method static RUSLAN_DISPLAY() Returns the RUSLAN_DISPLAY enum instance
 * @method static RUSSO_ONE() Returns the RUSSO_ONE enum instance
 * @method static RUTHIE() Returns the RUTHIE enum instance
 * @method static RYE() Returns the RYE enum instance
 * @method static SACRAMENTO() Returns the SACRAMENTO enum instance
 * @method static SAHITYA() Returns the SAHITYA enum instance
 * @method static SAIL() Returns the SAIL enum instance
 * @method static SAIRA() Returns the SAIRA enum instance
 * @method static SAIRA_CONDENSED() Returns the SAIRA_CONDENSED enum instance
 * @method static SAIRA_EXTRA_CONDENSED() Returns the SAIRA_EXTRA_CONDENSED enum instance
 * @method static SAIRA_SEMI_CONDENSED() Returns the SAIRA_SEMI_CONDENSED enum instance
 * @method static SALSA() Returns the SALSA enum instance
 * @method static SANCHEZ() Returns the SANCHEZ enum instance
 * @method static SANCREEK() Returns the SANCREEK enum instance
 * @method static SANSITA() Returns the SANSITA enum instance
 * @method static SARALA() Returns the SARALA enum instance
 * @method static SARINA() Returns the SARINA enum instance
 * @method static SARPANCH() Returns the SARPANCH enum instance
 * @method static SATISFY() Returns the SATISFY enum instance
 * @method static SCADA() Returns the SCADA enum instance
 * @method static SCHEHERAZADE() Returns the SCHEHERAZADE enum instance
 * @method static SCHOOLBELL() Returns the SCHOOLBELL enum instance
 * @method static SCOPE_ONE() Returns the SCOPE_ONE enum instance
 * @method static SEAWEED_SCRIPT() Returns the SEAWEED_SCRIPT enum instance
 * @method static SECULAR_ONE() Returns the SECULAR_ONE enum instance
 * @method static SEDGWICK_AVE() Returns the SEDGWICK_AVE enum instance
 * @method static SEDGWICK_AVE_DISPLAY() Returns the SEDGWICK_AVE_DISPLAY enum instance
 * @method static SEVILLANA() Returns the SEVILLANA enum instance
 * @method static SEYMOUR_ONE() Returns the SEYMOUR_ONE enum instance
 * @method static SHADOWS_INTO_LIGHT() Returns the SHADOWS_INTO_LIGHT enum instance
 * @method static SHADOWS_INTO_LIGHT_TWO() Returns the SHADOWS_INTO_LIGHT_TWO enum instance
 * @method static SHANTI() Returns the SHANTI enum instance
 * @method static SHARE() Returns the SHARE enum instance
 * @method static SHARE_TECH() Returns the SHARE_TECH enum instance
 * @method static SHARE_TECH_MONO() Returns the SHARE_TECH_MONO enum instance
 * @method static SHOJUMARU() Returns the SHOJUMARU enum instance
 * @method static SHORT_STACK() Returns the SHORT_STACK enum instance
 * @method static SHRIKHAND() Returns the SHRIKHAND enum instance
 * @method static SIEMREAP() Returns the SIEMREAP enum instance
 * @method static SIGMAR_ONE() Returns the SIGMAR_ONE enum instance
 * @method static SIGNIKA() Returns the SIGNIKA enum instance
 * @method static SIGNIKA_NEGATIVE() Returns the SIGNIKA_NEGATIVE enum instance
 * @method static SIMONETTA() Returns the SIMONETTA enum instance
 * @method static SINTONY() Returns the SINTONY enum instance
 * @method static SIRIN_STENCIL() Returns the SIRIN_STENCIL enum instance
 * @method static SIX_CAPS() Returns the SIX_CAPS enum instance
 * @method static SKRANJI() Returns the SKRANJI enum instance
 * @method static SLABO_13PX() Returns the SLABO_13PX enum instance
 * @method static SLABO_27PX() Returns the SLABO_27PX enum instance
 * @method static SLACKEY() Returns the SLACKEY enum instance
 * @method static SMOKUM() Returns the SMOKUM enum instance
 * @method static SMYTHE() Returns the SMYTHE enum instance
 * @method static SNIGLET() Returns the SNIGLET enum instance
 * @method static SNIPPET() Returns the SNIPPET enum instance
 * @method static SNOWBURST_ONE() Returns the SNOWBURST_ONE enum instance
 * @method static SOFADI_ONE() Returns the SOFADI_ONE enum instance
 * @method static SOFIA() Returns the SOFIA enum instance
 * @method static SONSIE_ONE() Returns the SONSIE_ONE enum instance
 * @method static SORTS_MILL_GOUDY() Returns the SORTS_MILL_GOUDY enum instance
 * @method static SOURCE_CODE_PRO() Returns the SOURCE_CODE_PRO enum instance
 * @method static SOURCE_SANS_PRO() Returns the SOURCE_SANS_PRO enum instance
 * @method static SOURCE_SERIF_PRO() Returns the SOURCE_SERIF_PRO enum instance
 * @method static SPACE_MONO() Returns the SPACE_MONO enum instance
 * @method static SPECIAL_ELITE() Returns the SPECIAL_ELITE enum instance
 * @method static SPECTRAL() Returns the SPECTRAL enum instance
 * @method static SPECTRAL_SC() Returns the SPECTRAL_SC enum instance
 * @method static SPICY_RICE() Returns the SPICY_RICE enum instance
 * @method static SPINNAKER() Returns the SPINNAKER enum instance
 * @method static SPIRAX() Returns the SPIRAX enum instance
 * @method static SQUADA_ONE() Returns the SQUADA_ONE enum instance
 * @method static SREE_KRUSHNADEVARAYA() Returns the SREE_KRUSHNADEVARAYA enum instance
 * @method static SRIRACHA() Returns the SRIRACHA enum instance
 * @method static STALEMATE() Returns the STALEMATE enum instance
 * @method static STALINIST_ONE() Returns the STALINIST_ONE enum instance
 * @method static STARDOS_STENCIL() Returns the STARDOS_STENCIL enum instance
 * @method static STINT_ULTRA_CONDENSED() Returns the STINT_ULTRA_CONDENSED enum instance
 * @method static STINT_ULTRA_EXPANDED() Returns the STINT_ULTRA_EXPANDED enum instance
 * @method static STOKE() Returns the STOKE enum instance
 * @method static STRAIT() Returns the STRAIT enum instance
 * @method static SUE_ELLEN_FRANCISCO() Returns the SUE_ELLEN_FRANCISCO enum instance
 * @method static SUEZ_ONE() Returns the SUEZ_ONE enum instance
 * @method static SUMANA() Returns the SUMANA enum instance
 * @method static SUNSHINEY() Returns the SUNSHINEY enum instance
 * @method static SUPERMERCADO_ONE() Returns the SUPERMERCADO_ONE enum instance
 * @method static SURA() Returns the SURA enum instance
 * @method static SURANNA() Returns the SURANNA enum instance
 * @method static SURAVARAM() Returns the SURAVARAM enum instance
 * @method static SUWANNAPHUM() Returns the SUWANNAPHUM enum instance
 * @method static SWANKY_AND_MOO_MOO() Returns the SWANKY_AND_MOO_MOO enum instance
 * @method static SYNCOPATE() Returns the SYNCOPATE enum instance
 * @method static TANGERINE() Returns the TANGERINE enum instance
 * @method static TAPROM() Returns the TAPROM enum instance
 * @method static TAURI() Returns the TAURI enum instance
 * @method static TAVIRAJ() Returns the TAVIRAJ enum instance
 * @method static TEKO() Returns the TEKO enum instance
 * @method static TELEX() Returns the TELEX enum instance
 * @method static TENALI_RAMAKRISHNA() Returns the TENALI_RAMAKRISHNA enum instance
 * @method static TENOR_SANS() Returns the TENOR_SANS enum instance
 * @method static TEXT_ME_ONE() Returns the TEXT_ME_ONE enum instance
 * @method static THE_GIRL_NEXT_DOOR() Returns the THE_GIRL_NEXT_DOOR enum instance
 * @method static TIENNE() Returns the TIENNE enum instance
 * @method static TILLANA() Returns the TILLANA enum instance
 * @method static TIMMANA() Returns the TIMMANA enum instance
 * @method static TINOS() Returns the TINOS enum instance
 * @method static TITAN_ONE() Returns the TITAN_ONE enum instance
 * @method static TITILLIUM_WEB() Returns the TITILLIUM_WEB enum instance
 * @method static TRADE_WINDS() Returns the TRADE_WINDS enum instance
 * @method static TRIRONG() Returns the TRIRONG enum instance
 * @method static TROCCHI() Returns the TROCCHI enum instance
 * @method static TROCHUT() Returns the TROCHUT enum instance
 * @method static TRYKKER() Returns the TRYKKER enum instance
 * @method static TULPEN_ONE() Returns the TULPEN_ONE enum instance
 * @method static UBUNTU() Returns the UBUNTU enum instance
 * @method static UBUNTU_CONDENSED() Returns the UBUNTU_CONDENSED enum instance
 * @method static UBUNTU_MONO() Returns the UBUNTU_MONO enum instance
 * @method static ULTRA() Returns the ULTRA enum instance
 * @method static UNCIAL_ANTIQUA() Returns the UNCIAL_ANTIQUA enum instance
 * @method static UNDERDOG() Returns the UNDERDOG enum instance
 * @method static UNICA_ONE() Returns the UNICA_ONE enum instance
 * @method static UNIFRAKTURCOOK() Returns the UNIFRAKTURCOOK enum instance
 * @method static UNIFRAKTURMAGUNTIA() Returns the UNIFRAKTURMAGUNTIA enum instance
 * @method static UNKEMPT() Returns the UNKEMPT enum instance
 * @method static UNLOCK() Returns the UNLOCK enum instance
 * @method static UNNA() Returns the UNNA enum instance
 * @method static VT323() Returns the VT323 enum instance
 * @method static VAMPIRO_ONE() Returns the VAMPIRO_ONE enum instance
 * @method static VARELA() Returns the VARELA enum instance
 * @method static VARELA_ROUND() Returns the VARELA_ROUND enum instance
 * @method static VAST_SHADOW() Returns the VAST_SHADOW enum instance
 * @method static VESPER_LIBRE() Returns the VESPER_LIBRE enum instance
 * @method static VIBUR() Returns the VIBUR enum instance
 * @method static VIDALOKA() Returns the VIDALOKA enum instance
 * @method static VIGA() Returns the VIGA enum instance
 * @method static VOCES() Returns the VOCES enum instance
 * @method static VOLKHOV() Returns the VOLKHOV enum instance
 * @method static VOLLKORN() Returns the VOLLKORN enum instance
 * @method static VOLLKORN_SC() Returns the VOLLKORN_SC enum instance
 * @method static VOLTAIRE() Returns the VOLTAIRE enum instance
 * @method static WAITING_FOR_THE_SUNRISE() Returns the WAITING_FOR_THE_SUNRISE enum instance
 * @method static WALLPOET() Returns the WALLPOET enum instance
 * @method static WALTER_TURNCOAT() Returns the WALTER_TURNCOAT enum instance
 * @method static WARNES() Returns the WARNES enum instance
 * @method static WELLFLEET() Returns the WELLFLEET enum instance
 * @method static WENDY_ONE() Returns the WENDY_ONE enum instance
 * @method static WIRE_ONE() Returns the WIRE_ONE enum instance
 * @method static WORK_SANS() Returns the WORK_SANS enum instance
 * @method static YANONE_KAFFEESATZ() Returns the YANONE_KAFFEESATZ enum instance
 * @method static YANTRAMANAV() Returns the YANTRAMANAV enum instance
 * @method static YATRA_ONE() Returns the YATRA_ONE enum instance
 * @method static YELLOWTAIL() Returns the YELLOWTAIL enum instance
 * @method static YESEVA_ONE() Returns the YESEVA_ONE enum instance
 * @method static YESTERYEAR() Returns the YESTERYEAR enum instance
 * @method static YRSA() Returns the YRSA enum instance
 * @method static ZEYADA() Returns the ZEYADA enum instance
 * @method static ZILLA_SLAB() Returns the ZILLA_SLAB enum instance
 * @method static ZILLA_SLAB_HIGHLIGHT() Returns the ZILLA_SLAB_HIGHLIGHT enum instance
 */
enum GoogleFont: string
{
    use Enum;

    /**
     * ABeeZee.
     */
    #[Label('ABeeZee')]
    #[Description('ABeeZee')]
    case ABEEZEE = 'ABeeZee';

    /**
     * Abel.
     */
    #[Label('Abel')]
    #[Description('Abel')]
    case ABEL = 'Abel';

    /**
     * Abhaya Libre.
     */
    #[Label('Abhaya Libre')]
    #[Description('Abhaya Libre')]
    case ABHAYA_LIBRE = 'Abhaya Libre';

    /**
     * Abril Fatface.
     */
    #[Label('Abril Fatface')]
    #[Description('Abril Fatface')]
    case ABRIL_FATFACE = 'Abril Fatface';

    /**
     * Aclonica.
     */
    #[Label('Aclonica')]
    #[Description('Aclonica')]
    case ACLONICA = 'Aclonica';

    /**
     * Acme.
     */
    #[Label('Acme')]
    #[Description('Acme')]
    case ACME = 'Acme';

    /**
     * Actor.
     */
    #[Label('Actor')]
    #[Description('Actor')]
    case ACTOR = 'Actor';

    /**
     * Adamina.
     */
    #[Label('Adamina')]
    #[Description('Adamina')]
    case ADAMINA = 'Adamina';

    /**
     * Advent Pro.
     */
    #[Label('Advent Pro')]
    #[Description('Advent Pro')]
    case ADVENT_PRO = 'Advent Pro';

    /**
     * Aguafina Script.
     */
    #[Label('Aguafina Script')]
    #[Description('Aguafina Script')]
    case AGUAFINA_SCRIPT = 'Aguafina Script';

    /**
     * Akronim.
     */
    #[Label('Akronim')]
    #[Description('Akronim')]
    case AKRONIM = 'Akronim';

    /**
     * Aladin.
     */
    #[Label('Aladin')]
    #[Description('Aladin')]
    case ALADIN = 'Aladin';

    /**
     * Aldrich.
     */
    #[Label('Aldrich')]
    #[Description('Aldrich')]
    case ALDRICH = 'Aldrich';

    /**
     * Alef.
     */
    #[Label('Alef')]
    #[Description('Alef')]
    case ALEF = 'Alef';

    /**
     * Alegreya.
     */
    #[Label('Alegreya')]
    #[Description('Alegreya')]
    case ALEGREYA = 'Alegreya';

    /**
     * Alegreya SC.
     */
    #[Label('Alegreya SC')]
    #[Description('Alegreya SC')]
    case ALEGREYA_SC = 'Alegreya SC';

    /**
     * Alegreya Sans.
     */
    #[Label('Alegreya Sans')]
    #[Description('Alegreya Sans')]
    case ALEGREYA_SANS = 'Alegreya Sans';

    /**
     * Alegreya Sans SC.
     */
    #[Label('Alegreya Sans SC')]
    #[Description('Alegreya Sans SC')]
    case ALEGREYA_SANS_SC = 'Alegreya Sans SC';

    /**
     * Alex Brush.
     */
    #[Label('Alex Brush')]
    #[Description('Alex Brush')]
    case ALEX_BRUSH = 'Alex Brush';

    /**
     * Alfa Slab One.
     */
    #[Label('Alfa Slab One')]
    #[Description('Alfa Slab One')]
    case ALFA_SLAB_ONE = 'Alfa Slab One';

    /**
     * Alice.
     */
    #[Label('Alice')]
    #[Description('Alice')]
    case ALICE = 'Alice';

    /**
     * Alike.
     */
    #[Label('Alike')]
    #[Description('Alike')]
    case ALIKE = 'Alike';

    /**
     * Alike Angular.
     */
    #[Label('Alike Angular')]
    #[Description('Alike Angular')]
    case ALIKE_ANGULAR = 'Alike Angular';

    /**
     * Allan.
     */
    #[Label('Allan')]
    #[Description('Allan')]
    case ALLAN = 'Allan';

    /**
     * Allerta.
     */
    #[Label('Allerta')]
    #[Description('Allerta')]
    case ALLERTA = 'Allerta';

    /**
     * Allerta Stencil.
     */
    #[Label('Allerta Stencil')]
    #[Description('Allerta Stencil')]
    case ALLERTA_STENCIL = 'Allerta Stencil';

    /**
     * Allura.
     */
    #[Label('Allura')]
    #[Description('Allura')]
    case ALLURA = 'Allura';

    /**
     * Almendra.
     */
    #[Label('Almendra')]
    #[Description('Almendra')]
    case ALMENDRA = 'Almendra';

    /**
     * Almendra Display.
     */
    #[Label('Almendra Display')]
    #[Description('Almendra Display')]
    case ALMENDRA_DISPLAY = 'Almendra Display';

    /**
     * Almendra SC.
     */
    #[Label('Almendra SC')]
    #[Description('Almendra SC')]
    case ALMENDRA_SC = 'Almendra SC';

    /**
     * Amarante.
     */
    #[Label('Amarante')]
    #[Description('Amarante')]
    case AMARANTE = 'Amarante';

    /**
     * Amaranth.
     */
    #[Label('Amaranth')]
    #[Description('Amaranth')]
    case AMARANTH = 'Amaranth';

    /**
     * Amatic SC.
     */
    #[Label('Amatic SC')]
    #[Description('Amatic SC')]
    case AMATIC_SC = 'Amatic SC';

    /**
     * Amethysta.
     */
    #[Label('Amethysta')]
    #[Description('Amethysta')]
    case AMETHYSTA = 'Amethysta';

    /**
     * Amiko.
     */
    #[Label('Amiko')]
    #[Description('Amiko')]
    case AMIKO = 'Amiko';

    /**
     * Amiri.
     */
    #[Label('Amiri')]
    #[Description('Amiri')]
    case AMIRI = 'Amiri';

    /**
     * Amita.
     */
    #[Label('Amita')]
    #[Description('Amita')]
    case AMITA = 'Amita';

    /**
     * Anaheim.
     */
    #[Label('Anaheim')]
    #[Description('Anaheim')]
    case ANAHEIM = 'Anaheim';

    /**
     * Andada.
     */
    #[Label('Andada')]
    #[Description('Andada')]
    case ANDADA = 'Andada';

    /**
     * Andika.
     */
    #[Label('Andika')]
    #[Description('Andika')]
    case ANDIKA = 'Andika';

    /**
     * Angkor.
     */
    #[Label('Angkor')]
    #[Description('Angkor')]
    case ANGKOR = 'Angkor';

    /**
     * Annie Use Your Telescope.
     */
    #[Label('Annie Use Your Telescope')]
    #[Description('Annie Use Your Telescope')]
    case ANNIE_USE_YOUR_TELESCOPE = 'Annie Use Your Telescope';

    /**
     * Anonymous Pro.
     */
    #[Label('Anonymous Pro')]
    #[Description('Anonymous Pro')]
    case ANONYMOUS_PRO = 'Anonymous Pro';

    /**
     * Antic.
     */
    #[Label('Antic')]
    #[Description('Antic')]
    case ANTIC = 'Antic';

    /**
     * Antic Didone.
     */
    #[Label('Antic Didone')]
    #[Description('Antic Didone')]
    case ANTIC_DIDONE = 'Antic Didone';

    /**
     * Antic Slab.
     */
    #[Label('Antic Slab')]
    #[Description('Antic Slab')]
    case ANTIC_SLAB = 'Antic Slab';

    /**
     * Anton.
     */
    #[Label('Anton')]
    #[Description('Anton')]
    case ANTON = 'Anton';

    /**
     * Arapey.
     */
    #[Label('Arapey')]
    #[Description('Arapey')]
    case ARAPEY = 'Arapey';

    /**
     * Arbutus.
     */
    #[Label('Arbutus')]
    #[Description('Arbutus')]
    case ARBUTUS = 'Arbutus';

    /**
     * Arbutus Slab.
     */
    #[Label('Arbutus Slab')]
    #[Description('Arbutus Slab')]
    case ARBUTUS_SLAB = 'Arbutus Slab';

    /**
     * Architects Daughter.
     */
    #[Label('Architects Daughter')]
    #[Description('Architects Daughter')]
    case ARCHITECTS_DAUGHTER = 'Architects Daughter';

    /**
     * Archivo.
     */
    #[Label('Archivo')]
    #[Description('Archivo')]
    case ARCHIVO = 'Archivo';

    /**
     * Archivo Black.
     */
    #[Label('Archivo Black')]
    #[Description('Archivo Black')]
    case ARCHIVO_BLACK = 'Archivo Black';

    /**
     * Archivo Narrow.
     */
    #[Label('Archivo Narrow')]
    #[Description('Archivo Narrow')]
    case ARCHIVO_NARROW = 'Archivo Narrow';

    /**
     * Aref Ruqaa.
     */
    #[Label('Aref Ruqaa')]
    #[Description('Aref Ruqaa')]
    case AREF_RUQAA = 'Aref Ruqaa';

    /**
     * Arima Madurai.
     */
    #[Label('Arima Madurai')]
    #[Description('Arima Madurai')]
    case ARIMA_MADURAI = 'Arima Madurai';

    /**
     * Arimo.
     */
    #[Label('Arimo')]
    #[Description('Arimo')]
    case ARIMO = 'Arimo';

    /**
     * Arizonia.
     */
    #[Label('Arizonia')]
    #[Description('Arizonia')]
    case ARIZONIA = 'Arizonia';

    /**
     * Armata.
     */
    #[Label('Armata')]
    #[Description('Armata')]
    case ARMATA = 'Armata';

    /**
     * Arsenal.
     */
    #[Label('Arsenal')]
    #[Description('Arsenal')]
    case ARSENAL = 'Arsenal';

    /**
     * Artifika.
     */
    #[Label('Artifika')]
    #[Description('Artifika')]
    case ARTIFIKA = 'Artifika';

    /**
     * Arvo.
     */
    #[Label('Arvo')]
    #[Description('Arvo')]
    case ARVO = 'Arvo';

    /**
     * Arya.
     */
    #[Label('Arya')]
    #[Description('Arya')]
    case ARYA = 'Arya';

    /**
     * Asap.
     */
    #[Label('Asap')]
    #[Description('Asap')]
    case ASAP = 'Asap';

    /**
     * Asap Condensed.
     */
    #[Label('Asap Condensed')]
    #[Description('Asap Condensed')]
    case ASAP_CONDENSED = 'Asap Condensed';

    /**
     * Asar.
     */
    #[Label('Asar')]
    #[Description('Asar')]
    case ASAR = 'Asar';

    /**
     * Asset.
     */
    #[Label('Asset')]
    #[Description('Asset')]
    case ASSET = 'Asset';

    /**
     * Assistant.
     */
    #[Label('Assistant')]
    #[Description('Assistant')]
    case ASSISTANT = 'Assistant';

    /**
     * Astloch.
     */
    #[Label('Astloch')]
    #[Description('Astloch')]
    case ASTLOCH = 'Astloch';

    /**
     * Asul.
     */
    #[Label('Asul')]
    #[Description('Asul')]
    case ASUL = 'Asul';

    /**
     * Athiti.
     */
    #[Label('Athiti')]
    #[Description('Athiti')]
    case ATHITI = 'Athiti';

    /**
     * Atma.
     */
    #[Label('Atma')]
    #[Description('Atma')]
    case ATMA = 'Atma';

    /**
     * Atomic Age.
     */
    #[Label('Atomic Age')]
    #[Description('Atomic Age')]
    case ATOMIC_AGE = 'Atomic Age';

    /**
     * Aubrey.
     */
    #[Label('Aubrey')]
    #[Description('Aubrey')]
    case AUBREY = 'Aubrey';

    /**
     * Audiowide.
     */
    #[Label('Audiowide')]
    #[Description('Audiowide')]
    case AUDIOWIDE = 'Audiowide';

    /**
     * Autour One.
     */
    #[Label('Autour One')]
    #[Description('Autour One')]
    case AUTOUR_ONE = 'Autour One';

    /**
     * Average.
     */
    #[Label('Average')]
    #[Description('Average')]
    case AVERAGE = 'Average';

    /**
     * Average Sans.
     */
    #[Label('Average Sans')]
    #[Description('Average Sans')]
    case AVERAGE_SANS = 'Average Sans';

    /**
     * Averia Gruesa Libre.
     */
    #[Label('Averia Gruesa Libre')]
    #[Description('Averia Gruesa Libre')]
    case AVERIA_GRUESA_LIBRE = 'Averia Gruesa Libre';

    /**
     * Averia Libre.
     */
    #[Label('Averia Libre')]
    #[Description('Averia Libre')]
    case AVERIA_LIBRE = 'Averia Libre';

    /**
     * Averia Sans Libre.
     */
    #[Label('Averia Sans Libre')]
    #[Description('Averia Sans Libre')]
    case AVERIA_SANS_LIBRE = 'Averia Sans Libre';

    /**
     * Averia Serif Libre.
     */
    #[Label('Averia Serif Libre')]
    #[Description('Averia Serif Libre')]
    case AVERIA_SERIF_LIBRE = 'Averia Serif Libre';

    /**
     * Bad Script.
     */
    #[Label('Bad Script')]
    #[Description('Bad Script')]
    case BAD_SCRIPT = 'Bad Script';

    /**
     * Bahiana.
     */
    #[Label('Bahiana')]
    #[Description('Bahiana')]
    case BAHIANA = 'Bahiana';

    /**
     * Baloo.
     */
    #[Label('Baloo')]
    #[Description('Baloo')]
    case BALOO = 'Baloo';

    /**
     * Baloo Bhai.
     */
    #[Label('Baloo Bhai')]
    #[Description('Baloo Bhai')]
    case BALOO_BHAI = 'Baloo Bhai';

    /**
     * Baloo Bhaijaan.
     */
    #[Label('Baloo Bhaijaan')]
    #[Description('Baloo Bhaijaan')]
    case BALOO_BHAIJAAN = 'Baloo Bhaijaan';

    /**
     * Baloo Bhaina.
     */
    #[Label('Baloo Bhaina')]
    #[Description('Baloo Bhaina')]
    case BALOO_BHAINA = 'Baloo Bhaina';

    /**
     * Baloo Chettan.
     */
    #[Label('Baloo Chettan')]
    #[Description('Baloo Chettan')]
    case BALOO_CHETTAN = 'Baloo Chettan';

    /**
     * Baloo Da.
     */
    #[Label('Baloo Da')]
    #[Description('Baloo Da')]
    case BALOO_DA = 'Baloo Da';

    /**
     * Baloo Paaji.
     */
    #[Label('Baloo Paaji')]
    #[Description('Baloo Paaji')]
    case BALOO_PAAJI = 'Baloo Paaji';

    /**
     * Baloo Tamma.
     */
    #[Label('Baloo Tamma')]
    #[Description('Baloo Tamma')]
    case BALOO_TAMMA = 'Baloo Tamma';

    /**
     * Baloo Tammudu.
     */
    #[Label('Baloo Tammudu')]
    #[Description('Baloo Tammudu')]
    case BALOO_TAMMUDU = 'Baloo Tammudu';

    /**
     * Baloo Thambi.
     */
    #[Label('Baloo Thambi')]
    #[Description('Baloo Thambi')]
    case BALOO_THAMBI = 'Baloo Thambi';

    /**
     * Balthazar.
     */
    #[Label('Balthazar')]
    #[Description('Balthazar')]
    case BALTHAZAR = 'Balthazar';

    /**
     * Bangers.
     */
    #[Label('Bangers')]
    #[Description('Bangers')]
    case BANGERS = 'Bangers';

    /**
     * Barlow.
     */
    #[Label('Barlow')]
    #[Description('Barlow')]
    case BARLOW = 'Barlow';

    /**
     * Barlow Condensed.
     */
    #[Label('Barlow Condensed')]
    #[Description('Barlow Condensed')]
    case BARLOW_CONDENSED = 'Barlow Condensed';

    /**
     * Barlow Semi Condensed.
     */
    #[Label('Barlow Semi Condensed')]
    #[Description('Barlow Semi Condensed')]
    case BARLOW_SEMI_CONDENSED = 'Barlow Semi Condensed';

    /**
     * Barrio.
     */
    #[Label('Barrio')]
    #[Description('Barrio')]
    case BARRIO = 'Barrio';

    /**
     * Basic.
     */
    #[Label('Basic')]
    #[Description('Basic')]
    case BASIC = 'Basic';

    /**
     * Battambang.
     */
    #[Label('Battambang')]
    #[Description('Battambang')]
    case BATTAMBANG = 'Battambang';

    /**
     * Baumans.
     */
    #[Label('Baumans')]
    #[Description('Baumans')]
    case BAUMANS = 'Baumans';

    /**
     * Bayon.
     */
    #[Label('Bayon')]
    #[Description('Bayon')]
    case BAYON = 'Bayon';

    /**
     * Belgrano.
     */
    #[Label('Belgrano')]
    #[Description('Belgrano')]
    case BELGRANO = 'Belgrano';

    /**
     * Bellefair.
     */
    #[Label('Bellefair')]
    #[Description('Bellefair')]
    case BELLEFAIR = 'Bellefair';

    /**
     * Belleza.
     */
    #[Label('Belleza')]
    #[Description('Belleza')]
    case BELLEZA = 'Belleza';

    /**
     * BenchNine.
     */
    #[Label('BenchNine')]
    #[Description('BenchNine')]
    case BENCHNINE = 'BenchNine';

    /**
     * Bentham.
     */
    #[Label('Bentham')]
    #[Description('Bentham')]
    case BENTHAM = 'Bentham';

    /**
     * Berkshire Swash.
     */
    #[Label('Berkshire Swash')]
    #[Description('Berkshire Swash')]
    case BERKSHIRE_SWASH = 'Berkshire Swash';

    /**
     * Bevan.
     */
    #[Label('Bevan')]
    #[Description('Bevan')]
    case BEVAN = 'Bevan';

    /**
     * Bigelow Rules.
     */
    #[Label('Bigelow Rules')]
    #[Description('Bigelow Rules')]
    case BIGELOW_RULES = 'Bigelow Rules';

    /**
     * Bigshot One.
     */
    #[Label('Bigshot One')]
    #[Description('Bigshot One')]
    case BIGSHOT_ONE = 'Bigshot One';

    /**
     * Bilbo.
     */
    #[Label('Bilbo')]
    #[Description('Bilbo')]
    case BILBO = 'Bilbo';

    /**
     * Bilbo Swash Caps.
     */
    #[Label('Bilbo Swash Caps')]
    #[Description('Bilbo Swash Caps')]
    case BILBO_SWASH_CAPS = 'Bilbo Swash Caps';

    /**
     * BioRhyme.
     */
    #[Label('BioRhyme')]
    #[Description('BioRhyme')]
    case BIORHYME = 'BioRhyme';

    /**
     * BioRhyme Expanded.
     */
    #[Label('BioRhyme Expanded')]
    #[Description('BioRhyme Expanded')]
    case BIORHYME_EXPANDED = 'BioRhyme Expanded';

    /**
     * Biryani.
     */
    #[Label('Biryani')]
    #[Description('Biryani')]
    case BIRYANI = 'Biryani';

    /**
     * Bitter.
     */
    #[Label('Bitter')]
    #[Description('Bitter')]
    case BITTER = 'Bitter';

    /**
     * Black Ops One.
     */
    #[Label('Black Ops One')]
    #[Description('Black Ops One')]
    case BLACK_OPS_ONE = 'Black Ops One';

    /**
     * Bokor.
     */
    #[Label('Bokor')]
    #[Description('Bokor')]
    case BOKOR = 'Bokor';

    /**
     * Bonbon.
     */
    #[Label('Bonbon')]
    #[Description('Bonbon')]
    case BONBON = 'Bonbon';

    /**
     * Boogaloo.
     */
    #[Label('Boogaloo')]
    #[Description('Boogaloo')]
    case BOOGALOO = 'Boogaloo';

    /**
     * Bowlby One.
     */
    #[Label('Bowlby One')]
    #[Description('Bowlby One')]
    case BOWLBY_ONE = 'Bowlby One';

    /**
     * Bowlby One SC.
     */
    #[Label('Bowlby One SC')]
    #[Description('Bowlby One SC')]
    case BOWLBY_ONE_SC = 'Bowlby One SC';

    /**
     * Brawler.
     */
    #[Label('Brawler')]
    #[Description('Brawler')]
    case BRAWLER = 'Brawler';

    /**
     * Bree Serif.
     */
    #[Label('Bree Serif')]
    #[Description('Bree Serif')]
    case BREE_SERIF = 'Bree Serif';

    /**
     * Bubblegum Sans.
     */
    #[Label('Bubblegum Sans')]
    #[Description('Bubblegum Sans')]
    case BUBBLEGUM_SANS = 'Bubblegum Sans';

    /**
     * Bubbler One.
     */
    #[Label('Bubbler One')]
    #[Description('Bubbler One')]
    case BUBBLER_ONE = 'Bubbler One';

    /**
     * Buda.
     */
    #[Label('Buda')]
    #[Description('Buda')]
    case BUDA = 'Buda';

    /**
     * Buenard.
     */
    #[Label('Buenard')]
    #[Description('Buenard')]
    case BUENARD = 'Buenard';

    /**
     * Bungee.
     */
    #[Label('Bungee')]
    #[Description('Bungee')]
    case BUNGEE = 'Bungee';

    /**
     * Bungee Hairline.
     */
    #[Label('Bungee Hairline')]
    #[Description('Bungee Hairline')]
    case BUNGEE_HAIRLINE = 'Bungee Hairline';

    /**
     * Bungee Inline.
     */
    #[Label('Bungee Inline')]
    #[Description('Bungee Inline')]
    case BUNGEE_INLINE = 'Bungee Inline';

    /**
     * Bungee Outline.
     */
    #[Label('Bungee Outline')]
    #[Description('Bungee Outline')]
    case BUNGEE_OUTLINE = 'Bungee Outline';

    /**
     * Bungee Shade.
     */
    #[Label('Bungee Shade')]
    #[Description('Bungee Shade')]
    case BUNGEE_SHADE = 'Bungee Shade';

    /**
     * Butcherman.
     */
    #[Label('Butcherman')]
    #[Description('Butcherman')]
    case BUTCHERMAN = 'Butcherman';

    /**
     * Butterfly Kids.
     */
    #[Label('Butterfly Kids')]
    #[Description('Butterfly Kids')]
    case BUTTERFLY_KIDS = 'Butterfly Kids';

    /**
     * Cabin.
     */
    #[Label('Cabin')]
    #[Description('Cabin')]
    case CABIN = 'Cabin';

    /**
     * Cabin Condensed.
     */
    #[Label('Cabin Condensed')]
    #[Description('Cabin Condensed')]
    case CABIN_CONDENSED = 'Cabin Condensed';

    /**
     * Cabin Sketch.
     */
    #[Label('Cabin Sketch')]
    #[Description('Cabin Sketch')]
    case CABIN_SKETCH = 'Cabin Sketch';

    /**
     * Caesar Dressing.
     */
    #[Label('Caesar Dressing')]
    #[Description('Caesar Dressing')]
    case CAESAR_DRESSING = 'Caesar Dressing';

    /**
     * Cagliostro.
     */
    #[Label('Cagliostro')]
    #[Description('Cagliostro')]
    case CAGLIOSTRO = 'Cagliostro';

    /**
     * Cairo.
     */
    #[Label('Cairo')]
    #[Description('Cairo')]
    case CAIRO = 'Cairo';

    /**
     * Calligraffitti.
     */
    #[Label('Calligraffitti')]
    #[Description('Calligraffitti')]
    case CALLIGRAFFITTI = 'Calligraffitti';

    /**
     * Cambay.
     */
    #[Label('Cambay')]
    #[Description('Cambay')]
    case CAMBAY = 'Cambay';

    /**
     * Cambo.
     */
    #[Label('Cambo')]
    #[Description('Cambo')]
    case CAMBO = 'Cambo';

    /**
     * Candal.
     */
    #[Label('Candal')]
    #[Description('Candal')]
    case CANDAL = 'Candal';

    /**
     * Cantarell.
     */
    #[Label('Cantarell')]
    #[Description('Cantarell')]
    case CANTARELL = 'Cantarell';

    /**
     * Cantata One.
     */
    #[Label('Cantata One')]
    #[Description('Cantata One')]
    case CANTATA_ONE = 'Cantata One';

    /**
     * Cantora One.
     */
    #[Label('Cantora One')]
    #[Description('Cantora One')]
    case CANTORA_ONE = 'Cantora One';

    /**
     * Capriola.
     */
    #[Label('Capriola')]
    #[Description('Capriola')]
    case CAPRIOLA = 'Capriola';

    /**
     * Cardo.
     */
    #[Label('Cardo')]
    #[Description('Cardo')]
    case CARDO = 'Cardo';

    /**
     * Carme.
     */
    #[Label('Carme')]
    #[Description('Carme')]
    case CARME = 'Carme';

    /**
     * Carrois Gothic.
     */
    #[Label('Carrois Gothic')]
    #[Description('Carrois Gothic')]
    case CARROIS_GOTHIC = 'Carrois Gothic';

    /**
     * Carrois Gothic SC.
     */
    #[Label('Carrois Gothic SC')]
    #[Description('Carrois Gothic SC')]
    case CARROIS_GOTHIC_SC = 'Carrois Gothic SC';

    /**
     * Carter One.
     */
    #[Label('Carter One')]
    #[Description('Carter One')]
    case CARTER_ONE = 'Carter One';

    /**
     * Catamaran.
     */
    #[Label('Catamaran')]
    #[Description('Catamaran')]
    case CATAMARAN = 'Catamaran';

    /**
     * Caudex.
     */
    #[Label('Caudex')]
    #[Description('Caudex')]
    case CAUDEX = 'Caudex';

    /**
     * Caveat.
     */
    #[Label('Caveat')]
    #[Description('Caveat')]
    case CAVEAT = 'Caveat';

    /**
     * Caveat Brush.
     */
    #[Label('Caveat Brush')]
    #[Description('Caveat Brush')]
    case CAVEAT_BRUSH = 'Caveat Brush';

    /**
     * Cedarville Cursive.
     */
    #[Label('Cedarville Cursive')]
    #[Description('Cedarville Cursive')]
    case CEDARVILLE_CURSIVE = 'Cedarville Cursive';

    /**
     * Ceviche One.
     */
    #[Label('Ceviche One')]
    #[Description('Ceviche One')]
    case CEVICHE_ONE = 'Ceviche One';

    /**
     * Changa.
     */
    #[Label('Changa')]
    #[Description('Changa')]
    case CHANGA = 'Changa';

    /**
     * Changa One.
     */
    #[Label('Changa One')]
    #[Description('Changa One')]
    case CHANGA_ONE = 'Changa One';

    /**
     * Chango.
     */
    #[Label('Chango')]
    #[Description('Chango')]
    case CHANGO = 'Chango';

    /**
     * Chathura.
     */
    #[Label('Chathura')]
    #[Description('Chathura')]
    case CHATHURA = 'Chathura';

    /**
     * Chau Philomene One.
     */
    #[Label('Chau Philomene One')]
    #[Description('Chau Philomene One')]
    case CHAU_PHILOMENE_ONE = 'Chau Philomene One';

    /**
     * Chela One.
     */
    #[Label('Chela One')]
    #[Description('Chela One')]
    case CHELA_ONE = 'Chela One';

    /**
     * Chelsea Market.
     */
    #[Label('Chelsea Market')]
    #[Description('Chelsea Market')]
    case CHELSEA_MARKET = 'Chelsea Market';

    /**
     * Chenla.
     */
    #[Label('Chenla')]
    #[Description('Chenla')]
    case CHENLA = 'Chenla';

    /**
     * Cherry Cream Soda.
     */
    #[Label('Cherry Cream Soda')]
    #[Description('Cherry Cream Soda')]
    case CHERRY_CREAM_SODA = 'Cherry Cream Soda';

    /**
     * Cherry Swash.
     */
    #[Label('Cherry Swash')]
    #[Description('Cherry Swash')]
    case CHERRY_SWASH = 'Cherry Swash';

    /**
     * Chewy.
     */
    #[Label('Chewy')]
    #[Description('Chewy')]
    case CHEWY = 'Chewy';

    /**
     * Chicle.
     */
    #[Label('Chicle')]
    #[Description('Chicle')]
    case CHICLE = 'Chicle';

    /**
     * Chivo.
     */
    #[Label('Chivo')]
    #[Description('Chivo')]
    case CHIVO = 'Chivo';

    /**
     * Chonburi.
     */
    #[Label('Chonburi')]
    #[Description('Chonburi')]
    case CHONBURI = 'Chonburi';

    /**
     * Cinzel.
     */
    #[Label('Cinzel')]
    #[Description('Cinzel')]
    case CINZEL = 'Cinzel';

    /**
     * Cinzel Decorative.
     */
    #[Label('Cinzel Decorative')]
    #[Description('Cinzel Decorative')]
    case CINZEL_DECORATIVE = 'Cinzel Decorative';

    /**
     * Clicker Script.
     */
    #[Label('Clicker Script')]
    #[Description('Clicker Script')]
    case CLICKER_SCRIPT = 'Clicker Script';

    /**
     * Coda.
     */
    #[Label('Coda')]
    #[Description('Coda')]
    case CODA = 'Coda';

    /**
     * Coda Caption.
     */
    #[Label('Coda Caption')]
    #[Description('Coda Caption')]
    case CODA_CAPTION = 'Coda Caption';

    /**
     * Codystar.
     */
    #[Label('Codystar')]
    #[Description('Codystar')]
    case CODYSTAR = 'Codystar';

    /**
     * Coiny.
     */
    #[Label('Coiny')]
    #[Description('Coiny')]
    case COINY = 'Coiny';

    /**
     * Combo.
     */
    #[Label('Combo')]
    #[Description('Combo')]
    case COMBO = 'Combo';

    /**
     * Comfortaa.
     */
    #[Label('Comfortaa')]
    #[Description('Comfortaa')]
    case COMFORTAA = 'Comfortaa';

    /**
     * Coming Soon.
     */
    #[Label('Coming Soon')]
    #[Description('Coming Soon')]
    case COMING_SOON = 'Coming Soon';

    /**
     * Concert One.
     */
    #[Label('Concert One')]
    #[Description('Concert One')]
    case CONCERT_ONE = 'Concert One';

    /**
     * Condiment.
     */
    #[Label('Condiment')]
    #[Description('Condiment')]
    case CONDIMENT = 'Condiment';

    /**
     * Content.
     */
    #[Label('Content')]
    #[Description('Content')]
    case CONTENT = 'Content';

    /**
     * Contrail One.
     */
    #[Label('Contrail One')]
    #[Description('Contrail One')]
    case CONTRAIL_ONE = 'Contrail One';

    /**
     * Convergence.
     */
    #[Label('Convergence')]
    #[Description('Convergence')]
    case CONVERGENCE = 'Convergence';

    /**
     * Cookie.
     */
    #[Label('Cookie')]
    #[Description('Cookie')]
    case COOKIE = 'Cookie';

    /**
     * Copse.
     */
    #[Label('Copse')]
    #[Description('Copse')]
    case COPSE = 'Copse';

    /**
     * Corben.
     */
    #[Label('Corben')]
    #[Description('Corben')]
    case CORBEN = 'Corben';

    /**
     * Cormorant.
     */
    #[Label('Cormorant')]
    #[Description('Cormorant')]
    case CORMORANT = 'Cormorant';

    /**
     * Cormorant Garamond.
     */
    #[Label('Cormorant Garamond')]
    #[Description('Cormorant Garamond')]
    case CORMORANT_GARAMOND = 'Cormorant Garamond';

    /**
     * Cormorant Infant.
     */
    #[Label('Cormorant Infant')]
    #[Description('Cormorant Infant')]
    case CORMORANT_INFANT = 'Cormorant Infant';

    /**
     * Cormorant SC.
     */
    #[Label('Cormorant SC')]
    #[Description('Cormorant SC')]
    case CORMORANT_SC = 'Cormorant SC';

    /**
     * Cormorant Unicase.
     */
    #[Label('Cormorant Unicase')]
    #[Description('Cormorant Unicase')]
    case CORMORANT_UNICASE = 'Cormorant Unicase';

    /**
     * Cormorant Upright.
     */
    #[Label('Cormorant Upright')]
    #[Description('Cormorant Upright')]
    case CORMORANT_UPRIGHT = 'Cormorant Upright';

    /**
     * Courgette.
     */
    #[Label('Courgette')]
    #[Description('Courgette')]
    case COURGETTE = 'Courgette';

    /**
     * Cousine.
     */
    #[Label('Cousine')]
    #[Description('Cousine')]
    case COUSINE = 'Cousine';

    /**
     * Coustard.
     */
    #[Label('Coustard')]
    #[Description('Coustard')]
    case COUSTARD = 'Coustard';

    /**
     * Covered By Your Grace.
     */
    #[Label('Covered By Your Grace')]
    #[Description('Covered By Your Grace')]
    case COVERED_BY_YOUR_GRACE = 'Covered By Your Grace';

    /**
     * Crafty Girls.
     */
    #[Label('Crafty Girls')]
    #[Description('Crafty Girls')]
    case CRAFTY_GIRLS = 'Crafty Girls';

    /**
     * Creepster.
     */
    #[Label('Creepster')]
    #[Description('Creepster')]
    case CREEPSTER = 'Creepster';

    /**
     * Crete Round.
     */
    #[Label('Crete Round')]
    #[Description('Crete Round')]
    case CRETE_ROUND = 'Crete Round';

    /**
     * Crimson Text.
     */
    #[Label('Crimson Text')]
    #[Description('Crimson Text')]
    case CRIMSON_TEXT = 'Crimson Text';

    /**
     * Croissant One.
     */
    #[Label('Croissant One')]
    #[Description('Croissant One')]
    case CROISSANT_ONE = 'Croissant One';

    /**
     * Crushed.
     */
    #[Label('Crushed')]
    #[Description('Crushed')]
    case CRUSHED = 'Crushed';

    /**
     * Cuprum.
     */
    #[Label('Cuprum')]
    #[Description('Cuprum')]
    case CUPRUM = 'Cuprum';

    /**
     * Cutive.
     */
    #[Label('Cutive')]
    #[Description('Cutive')]
    case CUTIVE = 'Cutive';

    /**
     * Cutive Mono.
     */
    #[Label('Cutive Mono')]
    #[Description('Cutive Mono')]
    case CUTIVE_MONO = 'Cutive Mono';

    /**
     * Damion.
     */
    #[Label('Damion')]
    #[Description('Damion')]
    case DAMION = 'Damion';

    /**
     * Dancing Script.
     */
    #[Label('Dancing Script')]
    #[Description('Dancing Script')]
    case DANCING_SCRIPT = 'Dancing Script';

    /**
     * Dangrek.
     */
    #[Label('Dangrek')]
    #[Description('Dangrek')]
    case DANGREK = 'Dangrek';

    /**
     * David Libre.
     */
    #[Label('David Libre')]
    #[Description('David Libre')]
    case DAVID_LIBRE = 'David Libre';

    /**
     * Dawning of a New.
     */
    #[Label('Dawning of a New')]
    #[Description('Dawning of a New')]
    case DAWNING_OF_A_NEW = 'Dawning of a New';

    /**
     * Days One.
     */
    #[Label('Days One')]
    #[Description('Days One')]
    case DAYS_ONE = 'Days One';

    /**
     * Dekko.
     */
    #[Label('Dekko')]
    #[Description('Dekko')]
    case DEKKO = 'Dekko';

    /**
     * Delius.
     */
    #[Label('Delius')]
    #[Description('Delius')]
    case DELIUS = 'Delius';

    /**
     * Delius Swash Caps.
     */
    #[Label('Delius Swash Caps')]
    #[Description('Delius Swash Caps')]
    case DELIUS_SWASH_CAPS = 'Delius Swash Caps';

    /**
     * Delius Unicase.
     */
    #[Label('Delius Unicase')]
    #[Description('Delius Unicase')]
    case DELIUS_UNICASE = 'Delius Unicase';

    /**
     * Della Respira.
     */
    #[Label('Della Respira')]
    #[Description('Della Respira')]
    case DELLA_RESPIRA = 'Della Respira';

    /**
     * Denk One.
     */
    #[Label('Denk One')]
    #[Description('Denk One')]
    case DENK_ONE = 'Denk One';

    /**
     * Devonshire.
     */
    #[Label('Devonshire')]
    #[Description('Devonshire')]
    case DEVONSHIRE = 'Devonshire';

    /**
     * Dhurjati.
     */
    #[Label('Dhurjati')]
    #[Description('Dhurjati')]
    case DHURJATI = 'Dhurjati';

    /**
     * Didact Gothic.
     */
    #[Label('Didact Gothic')]
    #[Description('Didact Gothic')]
    case DIDACT_GOTHIC = 'Didact Gothic';

    /**
     * Diplomata.
     */
    #[Label('Diplomata')]
    #[Description('Diplomata')]
    case DIPLOMATA = 'Diplomata';

    /**
     * Diplomata SC.
     */
    #[Label('Diplomata SC')]
    #[Description('Diplomata SC')]
    case DIPLOMATA_SC = 'Diplomata SC';

    /**
     * Domine.
     */
    #[Label('Domine')]
    #[Description('Domine')]
    case DOMINE = 'Domine';

    /**
     * Donegal One.
     */
    #[Label('Donegal One')]
    #[Description('Donegal One')]
    case DONEGAL_ONE = 'Donegal One';

    /**
     * Doppio One.
     */
    #[Label('Doppio One')]
    #[Description('Doppio One')]
    case DOPPIO_ONE = 'Doppio One';

    /**
     * Dorsa.
     */
    #[Label('Dorsa')]
    #[Description('Dorsa')]
    case DORSA = 'Dorsa';

    /**
     * Dosis.
     */
    #[Label('Dosis')]
    #[Description('Dosis')]
    case DOSIS = 'Dosis';

    /**
     * Dr Sugiyama.
     */
    #[Label('Dr Sugiyama')]
    #[Description('Dr Sugiyama')]
    case DR_SUGIYAMA = 'Dr Sugiyama';

    /**
     * Duru Sans.
     */
    #[Label('Duru Sans')]
    #[Description('Duru Sans')]
    case DURU_SANS = 'Duru Sans';

    /**
     * Dynalight.
     */
    #[Label('Dynalight')]
    #[Description('Dynalight')]
    case DYNALIGHT = 'Dynalight';

    /**
     * EB Garamond.
     */
    #[Label('EB Garamond')]
    #[Description('EB Garamond')]
    case EB_GARAMOND = 'EB Garamond';

    /**
     * Eagle Lake.
     */
    #[Label('Eagle Lake')]
    #[Description('Eagle Lake')]
    case EAGLE_LAKE = 'Eagle Lake';

    /**
     * Eater.
     */
    #[Label('Eater')]
    #[Description('Eater')]
    case EATER = 'Eater';

    /**
     * Economica.
     */
    #[Label('Economica')]
    #[Description('Economica')]
    case ECONOMICA = 'Economica';

    /**
     * Eczar.
     */
    #[Label('Eczar')]
    #[Description('Eczar')]
    case ECZAR = 'Eczar';

    /**
     * El Messiri.
     */
    #[Label('El Messiri')]
    #[Description('El Messiri')]
    case EL_MESSIRI = 'El Messiri';

    /**
     * Electrolize.
     */
    #[Label('Electrolize')]
    #[Description('Electrolize')]
    case ELECTROLIZE = 'Electrolize';

    /**
     * Elsie.
     */
    #[Label('Elsie')]
    #[Description('Elsie')]
    case ELSIE = 'Elsie';

    /**
     * Elsie Swash Caps.
     */
    #[Label('Elsie Swash Caps')]
    #[Description('Elsie Swash Caps')]
    case ELSIE_SWASH_CAPS = 'Elsie Swash Caps';

    /**
     * Emblema One.
     */
    #[Label('Emblema One')]
    #[Description('Emblema One')]
    case EMBLEMA_ONE = 'Emblema One';

    /**
     * Emilys Candy.
     */
    #[Label('Emilys Candy')]
    #[Description('Emilys Candy')]
    case EMILYS_CANDY = 'Emilys Candy';

    /**
     * Encode Sans.
     */
    #[Label('Encode Sans')]
    #[Description('Encode Sans')]
    case ENCODE_SANS = 'Encode Sans';

    /**
     * Encode Sans Condensed.
     */
    #[Label('Encode Sans Condensed')]
    #[Description('Encode Sans Condensed')]
    case ENCODE_SANS_CONDENSED = 'Encode Sans Condensed';

    /**
     * Encode Sans Expanded.
     */
    #[Label('Encode Sans Expanded')]
    #[Description('Encode Sans Expanded')]
    case ENCODE_SANS_EXPANDED = 'Encode Sans Expanded';

    /**
     * Encode Sans Semi Condensed.
     */
    #[Label('Encode Sans Semi Condensed')]
    #[Description('Encode Sans Semi Condensed')]
    case ENCODE_SANS_SEMI_CONDENSED = 'Encode Sans Semi Condensed';

    /**
     * Encode Sans Semi Expanded.
     */
    #[Label('Encode Sans Semi Expanded')]
    #[Description('Encode Sans Semi Expanded')]
    case ENCODE_SANS_SEMI_EXPANDED = 'Encode Sans Semi Expanded';

    /**
     * Engagement.
     */
    #[Label('Engagement')]
    #[Description('Engagement')]
    case ENGAGEMENT = 'Engagement';

    /**
     * Englebert.
     */
    #[Label('Englebert')]
    #[Description('Englebert')]
    case ENGLEBERT = 'Englebert';

    /**
     * Enriqueta.
     */
    #[Label('Enriqueta')]
    #[Description('Enriqueta')]
    case ENRIQUETA = 'Enriqueta';

    /**
     * Erica One.
     */
    #[Label('Erica One')]
    #[Description('Erica One')]
    case ERICA_ONE = 'Erica One';

    /**
     * Esteban.
     */
    #[Label('Esteban')]
    #[Description('Esteban')]
    case ESTEBAN = 'Esteban';

    /**
     * Euphoria Script.
     */
    #[Label('Euphoria Script')]
    #[Description('Euphoria Script')]
    case EUPHORIA_SCRIPT = 'Euphoria Script';

    /**
     * Ewert.
     */
    #[Label('Ewert')]
    #[Description('Ewert')]
    case EWERT = 'Ewert';

    /**
     * Exo.
     */
    #[Label('Exo')]
    #[Description('Exo')]
    case EXO = 'Exo';

    /**
     * Exo 2.
     */
    #[Label('Exo 2')]
    #[Description('Exo 2')]
    case EXO_2 = 'Exo 2';

    /**
     * Expletus Sans.
     */
    #[Label('Expletus Sans')]
    #[Description('Expletus Sans')]
    case EXPLETUS_SANS = 'Expletus Sans';

    /**
     * Fanwood Text.
     */
    #[Label('Fanwood Text')]
    #[Description('Fanwood Text')]
    case FANWOOD_TEXT = 'Fanwood Text';

    /**
     * Farsan.
     */
    #[Label('Farsan')]
    #[Description('Farsan')]
    case FARSAN = 'Farsan';

    /**
     * Fascinate.
     */
    #[Label('Fascinate')]
    #[Description('Fascinate')]
    case FASCINATE = 'Fascinate';

    /**
     * Fascinate Inline.
     */
    #[Label('Fascinate Inline')]
    #[Description('Fascinate Inline')]
    case FASCINATE_INLINE = 'Fascinate Inline';

    /**
     * Faster One.
     */
    #[Label('Faster One')]
    #[Description('Faster One')]
    case FASTER_ONE = 'Faster One';

    /**
     * Fasthand.
     */
    #[Label('Fasthand')]
    #[Description('Fasthand')]
    case FASTHAND = 'Fasthand';

    /**
     * Fauna One.
     */
    #[Label('Fauna One')]
    #[Description('Fauna One')]
    case FAUNA_ONE = 'Fauna One';

    /**
     * Faustina.
     */
    #[Label('Faustina')]
    #[Description('Faustina')]
    case FAUSTINA = 'Faustina';

    /**
     * Federant.
     */
    #[Label('Federant')]
    #[Description('Federant')]
    case FEDERANT = 'Federant';

    /**
     * Federo.
     */
    #[Label('Federo')]
    #[Description('Federo')]
    case FEDERO = 'Federo';

    /**
     * Felipa.
     */
    #[Label('Felipa')]
    #[Description('Felipa')]
    case FELIPA = 'Felipa';

    /**
     * Fenix.
     */
    #[Label('Fenix')]
    #[Description('Fenix')]
    case FENIX = 'Fenix';

    /**
     * Finger Paint.
     */
    #[Label('Finger Paint')]
    #[Description('Finger Paint')]
    case FINGER_PAINT = 'Finger Paint';

    /**
     * Fira Mono.
     */
    #[Label('Fira Mono')]
    #[Description('Fira Mono')]
    case FIRA_MONO = 'Fira Mono';

    /**
     * Fira Sans.
     */
    #[Label('Fira Sans')]
    #[Description('Fira Sans')]
    case FIRA_SANS = 'Fira Sans';

    /**
     * Fira Sans Condensed.
     */
    #[Label('Fira Sans Condensed')]
    #[Description('Fira Sans Condensed')]
    case FIRA_SANS_CONDENSED = 'Fira Sans Condensed';

    /**
     * Fira Sans Extra Condensed.
     */
    #[Label('Fira Sans Extra Condensed')]
    #[Description('Fira Sans Extra Condensed')]
    case FIRA_SANS_EXTRA_CONDENSED = 'Fira Sans Extra Condensed';

    /**
     * Fjalla One.
     */
    #[Label('Fjalla One')]
    #[Description('Fjalla One')]
    case FJALLA_ONE = 'Fjalla One';

    /**
     * Fjord One.
     */
    #[Label('Fjord One')]
    #[Description('Fjord One')]
    case FJORD_ONE = 'Fjord One';

    /**
     * Flamenco.
     */
    #[Label('Flamenco')]
    #[Description('Flamenco')]
    case FLAMENCO = 'Flamenco';

    /**
     * Flavors.
     */
    #[Label('Flavors')]
    #[Description('Flavors')]
    case FLAVORS = 'Flavors';

    /**
     * Fondamento.
     */
    #[Label('Fondamento')]
    #[Description('Fondamento')]
    case FONDAMENTO = 'Fondamento';

    /**
     * Fontdiner Swanky.
     */
    #[Label('Fontdiner Swanky')]
    #[Description('Fontdiner Swanky')]
    case FONTDINER_SWANKY = 'Fontdiner Swanky';

    /**
     * Forum.
     */
    #[Label('Forum')]
    #[Description('Forum')]
    case FORUM = 'Forum';

    /**
     * Francois One.
     */
    #[Label('Francois One')]
    #[Description('Francois One')]
    case FRANCOIS_ONE = 'Francois One';

    /**
     * Frank Ruhl Libre.
     */
    #[Label('Frank Ruhl Libre')]
    #[Description('Frank Ruhl Libre')]
    case FRANK_RUHL_LIBRE = 'Frank Ruhl Libre';

    /**
     * Freckle Face.
     */
    #[Label('Freckle Face')]
    #[Description('Freckle Face')]
    case FRECKLE_FACE = 'Freckle Face';

    /**
     * Fredericka the Great.
     */
    #[Label('Fredericka the Great')]
    #[Description('Fredericka the Great')]
    case FREDERICKA_THE_GREAT = 'Fredericka the Great';

    /**
     * Fredoka One.
     */
    #[Label('Fredoka One')]
    #[Description('Fredoka One')]
    case FREDOKA_ONE = 'Fredoka One';

    /**
     * Freehand.
     */
    #[Label('Freehand')]
    #[Description('Freehand')]
    case FREEHAND = 'Freehand';

    /**
     * Fresca.
     */
    #[Label('Fresca')]
    #[Description('Fresca')]
    case FRESCA = 'Fresca';

    /**
     * Frijole.
     */
    #[Label('Frijole')]
    #[Description('Frijole')]
    case FRIJOLE = 'Frijole';

    /**
     * Fruktur.
     */
    #[Label('Fruktur')]
    #[Description('Fruktur')]
    case FRUKTUR = 'Fruktur';

    /**
     * Fugaz One.
     */
    #[Label('Fugaz One')]
    #[Description('Fugaz One')]
    case FUGAZ_ONE = 'Fugaz One';

    /**
     * GFS Didot.
     */
    #[Label('GFS Didot')]
    #[Description('GFS Didot')]
    case GFS_DIDOT = 'GFS Didot';

    /**
     * GFS Neohellenic.
     */
    #[Label('GFS Neohellenic')]
    #[Description('GFS Neohellenic')]
    case GFS_NEOHELLENIC = 'GFS Neohellenic';

    /**
     * Gabriela.
     */
    #[Label('Gabriela')]
    #[Description('Gabriela')]
    case GABRIELA = 'Gabriela';

    /**
     * Gafata.
     */
    #[Label('Gafata')]
    #[Description('Gafata')]
    case GAFATA = 'Gafata';

    /**
     * Galada.
     */
    #[Label('Galada')]
    #[Description('Galada')]
    case GALADA = 'Galada';

    /**
     * Galdeano.
     */
    #[Label('Galdeano')]
    #[Description('Galdeano')]
    case GALDEANO = 'Galdeano';

    /**
     * Galindo.
     */
    #[Label('Galindo')]
    #[Description('Galindo')]
    case GALINDO = 'Galindo';

    /**
     * Gentium Basic.
     */
    #[Label('Gentium Basic')]
    #[Description('Gentium Basic')]
    case GENTIUM_BASIC = 'Gentium Basic';

    /**
     * Gentium Book Basic.
     */
    #[Label('Gentium Book Basic')]
    #[Description('Gentium Book Basic')]
    case GENTIUM_BOOK_BASIC = 'Gentium Book Basic';

    /**
     * Geo.
     */
    #[Label('Geo')]
    #[Description('Geo')]
    case GEO = 'Geo';

    /**
     * Geostar.
     */
    #[Label('Geostar')]
    #[Description('Geostar')]
    case GEOSTAR = 'Geostar';

    /**
     * Geostar Fill.
     */
    #[Label('Geostar Fill')]
    #[Description('Geostar Fill')]
    case GEOSTAR_FILL = 'Geostar Fill';

    /**
     * Germania One.
     */
    #[Label('Germania One')]
    #[Description('Germania One')]
    case GERMANIA_ONE = 'Germania One';

    /**
     * Gidugu.
     */
    #[Label('Gidugu')]
    #[Description('Gidugu')]
    case GIDUGU = 'Gidugu';

    /**
     * Gilda Display.
     */
    #[Label('Gilda Display')]
    #[Description('Gilda Display')]
    case GILDA_DISPLAY = 'Gilda Display';

    /**
     * Give You Glory.
     */
    #[Label('Give You Glory')]
    #[Description('Give You Glory')]
    case GIVE_YOU_GLORY = 'Give You Glory';

    /**
     * Glass Antiqua.
     */
    #[Label('Glass Antiqua')]
    #[Description('Glass Antiqua')]
    case GLASS_ANTIQUA = 'Glass Antiqua';

    /**
     * Glegoo.
     */
    #[Label('Glegoo')]
    #[Description('Glegoo')]
    case GLEGOO = 'Glegoo';

    /**
     * Gloria Hallelujah.
     */
    #[Label('Gloria Hallelujah')]
    #[Description('Gloria Hallelujah')]
    case GLORIA_HALLELUJAH = 'Gloria Hallelujah';

    /**
     * Goblin One.
     */
    #[Label('Goblin One')]
    #[Description('Goblin One')]
    case GOBLIN_ONE = 'Goblin One';

    /**
     * Gochi Hand.
     */
    #[Label('Gochi Hand')]
    #[Description('Gochi Hand')]
    case GOCHI_HAND = 'Gochi Hand';

    /**
     * Gorditas.
     */
    #[Label('Gorditas')]
    #[Description('Gorditas')]
    case GORDITAS = 'Gorditas';

    /**
     * Goudy Bookletter 1911.
     */
    #[Label('Goudy Bookletter 1911')]
    #[Description('Goudy Bookletter 1911')]
    case GOUDY_BOOKLETTER_1911 = 'Goudy Bookletter 1911';

    /**
     * Graduate.
     */
    #[Label('Graduate')]
    #[Description('Graduate')]
    case GRADUATE = 'Graduate';

    /**
     * Grand Hotel.
     */
    #[Label('Grand Hotel')]
    #[Description('Grand Hotel')]
    case GRAND_HOTEL = 'Grand Hotel';

    /**
     * Gravitas One.
     */
    #[Label('Gravitas One')]
    #[Description('Gravitas One')]
    case GRAVITAS_ONE = 'Gravitas One';

    /**
     * Great Vibes.
     */
    #[Label('Great Vibes')]
    #[Description('Great Vibes')]
    case GREAT_VIBES = 'Great Vibes';

    /**
     * Griffy.
     */
    #[Label('Griffy')]
    #[Description('Griffy')]
    case GRIFFY = 'Griffy';

    /**
     * Gruppo.
     */
    #[Label('Gruppo')]
    #[Description('Gruppo')]
    case GRUPPO = 'Gruppo';

    /**
     * Gudea.
     */
    #[Label('Gudea')]
    #[Description('Gudea')]
    case GUDEA = 'Gudea';

    /**
     * Gurajada.
     */
    #[Label('Gurajada')]
    #[Description('Gurajada')]
    case GURAJADA = 'Gurajada';

    /**
     * Habibi.
     */
    #[Label('Habibi')]
    #[Description('Habibi')]
    case HABIBI = 'Habibi';

    /**
     * Halant.
     */
    #[Label('Halant')]
    #[Description('Halant')]
    case HALANT = 'Halant';

    /**
     * Hammersmith One.
     */
    #[Label('Hammersmith One')]
    #[Description('Hammersmith One')]
    case HAMMERSMITH_ONE = 'Hammersmith One';

    /**
     * Hanalei.
     */
    #[Label('Hanalei')]
    #[Description('Hanalei')]
    case HANALEI = 'Hanalei';

    /**
     * Hanalei Fill.
     */
    #[Label('Hanalei Fill')]
    #[Description('Hanalei Fill')]
    case HANALEI_FILL = 'Hanalei Fill';

    /**
     * Handlee.
     */
    #[Label('Handlee')]
    #[Description('Handlee')]
    case HANDLEE = 'Handlee';

    /**
     * Hanuman.
     */
    #[Label('Hanuman')]
    #[Description('Hanuman')]
    case HANUMAN = 'Hanuman';

    /**
     * Happy Monkey.
     */
    #[Label('Happy Monkey')]
    #[Description('Happy Monkey')]
    case HAPPY_MONKEY = 'Happy Monkey';

    /**
     * Harmattan.
     */
    #[Label('Harmattan')]
    #[Description('Harmattan')]
    case HARMATTAN = 'Harmattan';

    /**
     * Headland One.
     */
    #[Label('Headland One')]
    #[Description('Headland One')]
    case HEADLAND_ONE = 'Headland One';

    /**
     * Heebo.
     */
    #[Label('Heebo')]
    #[Description('Heebo')]
    case HEEBO = 'Heebo';

    /**
     * Henny Penny.
     */
    #[Label('Henny Penny')]
    #[Description('Henny Penny')]
    case HENNY_PENNY = 'Henny Penny';

    /**
     * Herr Von Muellerhoff.
     */
    #[Label('Herr Von Muellerhoff')]
    #[Description('Herr Von Muellerhoff')]
    case HERR_VON_MUELLERHOFF = 'Herr Von Muellerhoff';

    /**
     * Hind.
     */
    #[Label('Hind')]
    #[Description('Hind')]
    case HIND = 'Hind';

    /**
     * Hind Guntur.
     */
    #[Label('Hind Guntur')]
    #[Description('Hind Guntur')]
    case HIND_GUNTUR = 'Hind Guntur';

    /**
     * Hind Madurai.
     */
    #[Label('Hind Madurai')]
    #[Description('Hind Madurai')]
    case HIND_MADURAI = 'Hind Madurai';

    /**
     * Hind Siliguri.
     */
    #[Label('Hind Siliguri')]
    #[Description('Hind Siliguri')]
    case HIND_SILIGURI = 'Hind Siliguri';

    /**
     * Hind Vadodara.
     */
    #[Label('Hind Vadodara')]
    #[Description('Hind Vadodara')]
    case HIND_VADODARA = 'Hind Vadodara';

    /**
     * Holtwood One SC.
     */
    #[Label('Holtwood One SC')]
    #[Description('Holtwood One SC')]
    case HOLTWOOD_ONE_SC = 'Holtwood One SC';

    /**
     * Homemade Apple.
     */
    #[Label('Homemade Apple')]
    #[Description('Homemade Apple')]
    case HOMEMADE_APPLE = 'Homemade Apple';

    /**
     * Homenaje.
     */
    #[Label('Homenaje')]
    #[Description('Homenaje')]
    case HOMENAJE = 'Homenaje';

    /**
     * IM Fell DW Pica.
     */
    #[Label('IM Fell DW Pica')]
    #[Description('IM Fell DW Pica')]
    case IM_FELL_DW_PICA = 'IM Fell DW Pica';

    /**
     * IM Fell Double Pica.
     */
    #[Label('IM Fell Double Pica')]
    #[Description('IM Fell Double Pica')]
    case IM_FELL_DOUBLE_PICA = 'IM Fell Double Pica';

    /**
     * IM Fell English.
     */
    #[Label('IM Fell English')]
    #[Description('IM Fell English')]
    case IM_FELL_ENGLISH = 'IM Fell English';

    /**
     * IM Fell English SC.
     */
    #[Label('IM Fell English SC')]
    #[Description('IM Fell English SC')]
    case IM_FELL_ENGLISH_SC = 'IM Fell English SC';

    /**
     * IM Fell French Canon.
     */
    #[Label('IM Fell French Canon')]
    #[Description('IM Fell French Canon')]
    case IM_FELL_FRENCH_CANON = 'IM Fell French Canon';

    /**
     * IM Fell Great Primer.
     */
    #[Label('IM Fell Great Primer')]
    #[Description('IM Fell Great Primer')]
    case IM_FELL_GREAT_PRIMER = 'IM Fell Great Primer';

    /**
     * Iceberg.
     */
    #[Label('Iceberg')]
    #[Description('Iceberg')]
    case ICEBERG = 'Iceberg';

    /**
     * Iceland.
     */
    #[Label('Iceland')]
    #[Description('Iceland')]
    case ICELAND = 'Iceland';

    /**
     * Imprima.
     */
    #[Label('Imprima')]
    #[Description('Imprima')]
    case IMPRIMA = 'Imprima';

    /**
     * Inconsolata.
     */
    #[Label('Inconsolata')]
    #[Description('Inconsolata')]
    case INCONSOLATA = 'Inconsolata';

    /**
     * Inder.
     */
    #[Label('Inder')]
    #[Description('Inder')]
    case INDER = 'Inder';

    /**
     * Indie Flower.
     */
    #[Label('Indie Flower')]
    #[Description('Indie Flower')]
    case INDIE_FLOWER = 'Indie Flower';

    /**
     * Inika.
     */
    #[Label('Inika')]
    #[Description('Inika')]
    case INIKA = 'Inika';

    /**
     * Inknut Antiqua.
     */
    #[Label('Inknut Antiqua')]
    #[Description('Inknut Antiqua')]
    case INKNUT_ANTIQUA = 'Inknut Antiqua';

    /**
     * Irish Grover.
     */
    #[Label('Irish Grover')]
    #[Description('Irish Grover')]
    case IRISH_GROVER = 'Irish Grover';

    /**
     * Istok Web.
     */
    #[Label('Istok Web')]
    #[Description('Istok Web')]
    case ISTOK_WEB = 'Istok Web';

    /**
     * Italiana.
     */
    #[Label('Italiana')]
    #[Description('Italiana')]
    case ITALIANA = 'Italiana';

    /**
     * Italianno.
     */
    #[Label('Italianno')]
    #[Description('Italianno')]
    case ITALIANNO = 'Italianno';

    /**
     * Itim.
     */
    #[Label('Itim')]
    #[Description('Itim')]
    case ITIM = 'Itim';

    /**
     * Jacques Francois.
     */
    #[Label('Jacques Francois')]
    #[Description('Jacques Francois')]
    case JACQUES_FRANCOIS = 'Jacques Francois';

    /**
     * Jacques Francois Shadow.
     */
    #[Label('Jacques Francois Shadow')]
    #[Description('Jacques Francois Shadow')]
    case JACQUES_FRANCOIS_SHADOW = 'Jacques Francois Shadow';

    /**
     * Jaldi.
     */
    #[Label('Jaldi')]
    #[Description('Jaldi')]
    case JALDI = 'Jaldi';

    /**
     * Jim Nightshade.
     */
    #[Label('Jim Nightshade')]
    #[Description('Jim Nightshade')]
    case JIM_NIGHTSHADE = 'Jim Nightshade';

    /**
     * Jockey One.
     */
    #[Label('Jockey One')]
    #[Description('Jockey One')]
    case JOCKEY_ONE = 'Jockey One';

    /**
     * Jolly Lodger.
     */
    #[Label('Jolly Lodger')]
    #[Description('Jolly Lodger')]
    case JOLLY_LODGER = 'Jolly Lodger';

    /**
     * Jomhuria.
     */
    #[Label('Jomhuria')]
    #[Description('Jomhuria')]
    case JOMHURIA = 'Jomhuria';

    /**
     * Josefin Sans.
     */
    #[Label('Josefin Sans')]
    #[Description('Josefin Sans')]
    case JOSEFIN_SANS = 'Josefin Sans';

    /**
     * Josefin Slab.
     */
    #[Label('Josefin Slab')]
    #[Description('Josefin Slab')]
    case JOSEFIN_SLAB = 'Josefin Slab';

    /**
     * Joti One.
     */
    #[Label('Joti One')]
    #[Description('Joti One')]
    case JOTI_ONE = 'Joti One';

    /**
     * Judson.
     */
    #[Label('Judson')]
    #[Description('Judson')]
    case JUDSON = 'Judson';

    /**
     * Julee.
     */
    #[Label('Julee')]
    #[Description('Julee')]
    case JULEE = 'Julee';

    /**
     * Julius Sans One.
     */
    #[Label('Julius Sans One')]
    #[Description('Julius Sans One')]
    case JULIUS_SANS_ONE = 'Julius Sans One';

    /**
     * Junge.
     */
    #[Label('Junge')]
    #[Description('Junge')]
    case JUNGE = 'Junge';

    /**
     * Jura.
     */
    #[Label('Jura')]
    #[Description('Jura')]
    case JURA = 'Jura';

    /**
     * Just Another Hand.
     */
    #[Label('Just Another Hand')]
    #[Description('Just Another Hand')]
    case JUST_ANOTHER_HAND = 'Just Another Hand';

    /**
     * Just Me Again Down.
     */
    #[Label('Just Me Again Down')]
    #[Description('Just Me Again Down')]
    case JUST_ME_AGAIN_DOWN = 'Just Me Again Down';

    /**
     * Kadwa.
     */
    #[Label('Kadwa')]
    #[Description('Kadwa')]
    case KADWA = 'Kadwa';

    /**
     * Kalam.
     */
    #[Label('Kalam')]
    #[Description('Kalam')]
    case KALAM = 'Kalam';

    /**
     * Kameron.
     */
    #[Label('Kameron')]
    #[Description('Kameron')]
    case KAMERON = 'Kameron';

    /**
     * Kanit.
     */
    #[Label('Kanit')]
    #[Description('Kanit')]
    case KANIT = 'Kanit';

    /**
     * Kantumruy.
     */
    #[Label('Kantumruy')]
    #[Description('Kantumruy')]
    case KANTUMRUY = 'Kantumruy';

    /**
     * Karla.
     */
    #[Label('Karla')]
    #[Description('Karla')]
    case KARLA = 'Karla';

    /**
     * Karma.
     */
    #[Label('Karma')]
    #[Description('Karma')]
    case KARMA = 'Karma';

    /**
     * Katibeh.
     */
    #[Label('Katibeh')]
    #[Description('Katibeh')]
    case KATIBEH = 'Katibeh';

    /**
     * Kaushan Script.
     */
    #[Label('Kaushan Script')]
    #[Description('Kaushan Script')]
    case KAUSHAN_SCRIPT = 'Kaushan Script';

    /**
     * Kavivanar.
     */
    #[Label('Kavivanar')]
    #[Description('Kavivanar')]
    case KAVIVANAR = 'Kavivanar';

    /**
     * Kavoon.
     */
    #[Label('Kavoon')]
    #[Description('Kavoon')]
    case KAVOON = 'Kavoon';

    /**
     * Kdam Thmor.
     */
    #[Label('Kdam Thmor')]
    #[Description('Kdam Thmor')]
    case KDAM_THMOR = 'Kdam Thmor';

    /**
     * Keania One.
     */
    #[Label('Keania One')]
    #[Description('Keania One')]
    case KEANIA_ONE = 'Keania One';

    /**
     * Kelly Slab.
     */
    #[Label('Kelly Slab')]
    #[Description('Kelly Slab')]
    case KELLY_SLAB = 'Kelly Slab';

    /**
     * Kenia.
     */
    #[Label('Kenia')]
    #[Description('Kenia')]
    case KENIA = 'Kenia';

    /**
     * Khand.
     */
    #[Label('Khand')]
    #[Description('Khand')]
    case KHAND = 'Khand';

    /**
     * Khmer.
     */
    #[Label('Khmer')]
    #[Description('Khmer')]
    case KHMER = 'Khmer';

    /**
     * Khula.
     */
    #[Label('Khula')]
    #[Description('Khula')]
    case KHULA = 'Khula';

    /**
     * Kite One.
     */
    #[Label('Kite One')]
    #[Description('Kite One')]
    case KITE_ONE = 'Kite One';

    /**
     * Knewave.
     */
    #[Label('Knewave')]
    #[Description('Knewave')]
    case KNEWAVE = 'Knewave';

    /**
     * Kotta One.
     */
    #[Label('Kotta One')]
    #[Description('Kotta One')]
    case KOTTA_ONE = 'Kotta One';

    /**
     * Koulen.
     */
    #[Label('Koulen')]
    #[Description('Koulen')]
    case KOULEN = 'Koulen';

    /**
     * Kranky.
     */
    #[Label('Kranky')]
    #[Description('Kranky')]
    case KRANKY = 'Kranky';

    /**
     * Kreon.
     */
    #[Label('Kreon')]
    #[Description('Kreon')]
    case KREON = 'Kreon';

    /**
     * Kristi.
     */
    #[Label('Kristi')]
    #[Description('Kristi')]
    case KRISTI = 'Kristi';

    /**
     * Krona One.
     */
    #[Label('Krona One')]
    #[Description('Krona One')]
    case KRONA_ONE = 'Krona One';

    /**
     * Kumar One.
     */
    #[Label('Kumar One')]
    #[Description('Kumar One')]
    case KUMAR_ONE = 'Kumar One';

    /**
     * Kumar One Outline.
     */
    #[Label('Kumar One Outline')]
    #[Description('Kumar One Outline')]
    case KUMAR_ONE_OUTLINE = 'Kumar One Outline';

    /**
     * Kurale.
     */
    #[Label('Kurale')]
    #[Description('Kurale')]
    case KURALE = 'Kurale';

    /**
     * La Belle Aurore.
     */
    #[Label('La Belle Aurore')]
    #[Description('La Belle Aurore')]
    case LA_BELLE_AURORE = 'La Belle Aurore';

    /**
     * Laila.
     */
    #[Label('Laila')]
    #[Description('Laila')]
    case LAILA = 'Laila';

    /**
     * Lakki Reddy.
     */
    #[Label('Lakki Reddy')]
    #[Description('Lakki Reddy')]
    case LAKKI_REDDY = 'Lakki Reddy';

    /**
     * Lalezar.
     */
    #[Label('Lalezar')]
    #[Description('Lalezar')]
    case LALEZAR = 'Lalezar';

    /**
     * Lancelot.
     */
    #[Label('Lancelot')]
    #[Description('Lancelot')]
    case LANCELOT = 'Lancelot';

    /**
     * Lateef.
     */
    #[Label('Lateef')]
    #[Description('Lateef')]
    case LATEEF = 'Lateef';

    /**
     * Lato.
     */
    #[Label('Lato')]
    #[Description('Lato')]
    case LATO = 'Lato';

    /**
     * League Script.
     */
    #[Label('League Script')]
    #[Description('League Script')]
    case LEAGUE_SCRIPT = 'League Script';

    /**
     * Leckerli One.
     */
    #[Label('Leckerli One')]
    #[Description('Leckerli One')]
    case LECKERLI_ONE = 'Leckerli One';

    /**
     * Ledger.
     */
    #[Label('Ledger')]
    #[Description('Ledger')]
    case LEDGER = 'Ledger';

    /**
     * Lekton.
     */
    #[Label('Lekton')]
    #[Description('Lekton')]
    case LEKTON = 'Lekton';

    /**
     * Lemon.
     */
    #[Label('Lemon')]
    #[Description('Lemon')]
    case LEMON = 'Lemon';

    /**
     * Lemonada.
     */
    #[Label('Lemonada')]
    #[Description('Lemonada')]
    case LEMONADA = 'Lemonada';

    /**
     * Libre Barcode 128.
     */
    #[Label('Libre Barcode 128')]
    #[Description('Libre Barcode 128')]
    case LIBRE_BARCODE_128 = 'Libre Barcode 128';

    /**
     * Libre Barcode 128 Text.
     */
    #[Label('Libre Barcode 128 Text')]
    #[Description('Libre Barcode 128 Text')]
    case LIBRE_BARCODE_128_TEXT = 'Libre Barcode 128 Text';

    /**
     * Libre Barcode 39.
     */
    #[Label('Libre Barcode 39')]
    #[Description('Libre Barcode 39')]
    case LIBRE_BARCODE_39 = 'Libre Barcode 39';

    /**
     * Libre Barcode 39 Extended.
     */
    #[Label('Libre Barcode 39 Extended')]
    #[Description('Libre Barcode 39 Extended')]
    case LIBRE_BARCODE_39_EXTENDED = 'Libre Barcode 39 Extended';

    /**
     * Libre Barcode 39 Text.
     */
    #[Label('Libre Barcode 39 Text')]
    #[Description('Libre Barcode 39 Text')]
    case LIBRE_BARCODE_39_TEXT = 'Libre Barcode 39 Text';

    /**
     * Libre Baskerville.
     */
    #[Label('Libre Baskerville')]
    #[Description('Libre Baskerville')]
    case LIBRE_BASKERVILLE = 'Libre Baskerville';

    /**
     * Libre Franklin.
     */
    #[Label('Libre Franklin')]
    #[Description('Libre Franklin')]
    case LIBRE_FRANKLIN = 'Libre Franklin';

    /**
     * Life Savers.
     */
    #[Label('Life Savers')]
    #[Description('Life Savers')]
    case LIFE_SAVERS = 'Life Savers';

    /**
     * Lilita One.
     */
    #[Label('Lilita One')]
    #[Description('Lilita One')]
    case LILITA_ONE = 'Lilita One';

    /**
     * Lily Script One.
     */
    #[Label('Lily Script One')]
    #[Description('Lily Script One')]
    case LILY_SCRIPT_ONE = 'Lily Script One';

    /**
     * Limelight.
     */
    #[Label('Limelight')]
    #[Description('Limelight')]
    case LIMELIGHT = 'Limelight';

    /**
     * Linden Hill.
     */
    #[Label('Linden Hill')]
    #[Description('Linden Hill')]
    case LINDEN_HILL = 'Linden Hill';

    /**
     * Lobster.
     */
    #[Label('Lobster')]
    #[Description('Lobster')]
    case LOBSTER = 'Lobster';

    /**
     * Lobster Two.
     */
    #[Label('Lobster Two')]
    #[Description('Lobster Two')]
    case LOBSTER_TWO = 'Lobster Two';

    /**
     * Londrina Outline.
     */
    #[Label('Londrina Outline')]
    #[Description('Londrina Outline')]
    case LONDRINA_OUTLINE = 'Londrina Outline';

    /**
     * Londrina Shadow.
     */
    #[Label('Londrina Shadow')]
    #[Description('Londrina Shadow')]
    case LONDRINA_SHADOW = 'Londrina Shadow';

    /**
     * Londrina Sketch.
     */
    #[Label('Londrina Sketch')]
    #[Description('Londrina Sketch')]
    case LONDRINA_SKETCH = 'Londrina Sketch';

    /**
     * Londrina Solid.
     */
    #[Label('Londrina Solid')]
    #[Description('Londrina Solid')]
    case LONDRINA_SOLID = 'Londrina Solid';

    /**
     * Lora.
     */
    #[Label('Lora')]
    #[Description('Lora')]
    case LORA = 'Lora';

    /**
     * Love Ya Like A.
     */
    #[Label('Love Ya Like A')]
    #[Description('Love Ya Like A')]
    case LOVE_YA_LIKE_A = 'Love Ya Like A';

    /**
     * Loved by the King.
     */
    #[Label('Loved by the King')]
    #[Description('Loved by the King')]
    case LOVED_BY_THE_KING = 'Loved by the King';

    /**
     * Lovers Quarrel.
     */
    #[Label('Lovers Quarrel')]
    #[Description('Lovers Quarrel')]
    case LOVERS_QUARREL = 'Lovers Quarrel';

    /**
     * Luckiest Guy.
     */
    #[Label('Luckiest Guy')]
    #[Description('Luckiest Guy')]
    case LUCKIEST_GUY = 'Luckiest Guy';

    /**
     * Lusitana.
     */
    #[Label('Lusitana')]
    #[Description('Lusitana')]
    case LUSITANA = 'Lusitana';

    /**
     * Lustria.
     */
    #[Label('Lustria')]
    #[Description('Lustria')]
    case LUSTRIA = 'Lustria';

    /**
     * Macondo.
     */
    #[Label('Macondo')]
    #[Description('Macondo')]
    case MACONDO = 'Macondo';

    /**
     * Macondo Swash Caps.
     */
    #[Label('Macondo Swash Caps')]
    #[Description('Macondo Swash Caps')]
    case MACONDO_SWASH_CAPS = 'Macondo Swash Caps';

    /**
     * Mada.
     */
    #[Label('Mada')]
    #[Description('Mada')]
    case MADA = 'Mada';

    /**
     * Magra.
     */
    #[Label('Magra')]
    #[Description('Magra')]
    case MAGRA = 'Magra';

    /**
     * Maiden Orange.
     */
    #[Label('Maiden Orange')]
    #[Description('Maiden Orange')]
    case MAIDEN_ORANGE = 'Maiden Orange';

    /**
     * Maitree.
     */
    #[Label('Maitree')]
    #[Description('Maitree')]
    case MAITREE = 'Maitree';

    /**
     * Mako.
     */
    #[Label('Mako')]
    #[Description('Mako')]
    case MAKO = 'Mako';

    /**
     * Mallanna.
     */
    #[Label('Mallanna')]
    #[Description('Mallanna')]
    case MALLANNA = 'Mallanna';

    /**
     * Mandali.
     */
    #[Label('Mandali')]
    #[Description('Mandali')]
    case MANDALI = 'Mandali';

    /**
     * Manuale.
     */
    #[Label('Manuale')]
    #[Description('Manuale')]
    case MANUALE = 'Manuale';

    /**
     * Marcellus.
     */
    #[Label('Marcellus')]
    #[Description('Marcellus')]
    case MARCELLUS = 'Marcellus';

    /**
     * Marcellus SC.
     */
    #[Label('Marcellus SC')]
    #[Description('Marcellus SC')]
    case MARCELLUS_SC = 'Marcellus SC';

    /**
     * Marck Script.
     */
    #[Label('Marck Script')]
    #[Description('Marck Script')]
    case MARCK_SCRIPT = 'Marck Script';

    /**
     * Margarine.
     */
    #[Label('Margarine')]
    #[Description('Margarine')]
    case MARGARINE = 'Margarine';

    /**
     * Marko One.
     */
    #[Label('Marko One')]
    #[Description('Marko One')]
    case MARKO_ONE = 'Marko One';

    /**
     * Marmelad.
     */
    #[Label('Marmelad')]
    #[Description('Marmelad')]
    case MARMELAD = 'Marmelad';

    /**
     * Martel.
     */
    #[Label('Martel')]
    #[Description('Martel')]
    case MARTEL = 'Martel';

    /**
     * Martel Sans.
     */
    #[Label('Martel Sans')]
    #[Description('Martel Sans')]
    case MARTEL_SANS = 'Martel Sans';

    /**
     * Marvel.
     */
    #[Label('Marvel')]
    #[Description('Marvel')]
    case MARVEL = 'Marvel';

    /**
     * Mate.
     */
    #[Label('Mate')]
    #[Description('Mate')]
    case MATE = 'Mate';

    /**
     * Mate SC.
     */
    #[Label('Mate SC')]
    #[Description('Mate SC')]
    case MATE_SC = 'Mate SC';

    /**
     * Maven Pro.
     */
    #[Label('Maven Pro')]
    #[Description('Maven Pro')]
    case MAVEN_PRO = 'Maven Pro';

    /**
     * McLaren.
     */
    #[Label('McLaren')]
    #[Description('McLaren')]
    case MCLAREN = 'McLaren';

    /**
     * Meddon.
     */
    #[Label('Meddon')]
    #[Description('Meddon')]
    case MEDDON = 'Meddon';

    /**
     * MedievalSharp.
     */
    #[Label('MedievalSharp')]
    #[Description('MedievalSharp')]
    case MEDIEVALSHARP = 'MedievalSharp';

    /**
     * Medula One.
     */
    #[Label('Medula One')]
    #[Description('Medula One')]
    case MEDULA_ONE = 'Medula One';

    /**
     * Meera Inimai.
     */
    #[Label('Meera Inimai')]
    #[Description('Meera Inimai')]
    case MEERA_INIMAI = 'Meera Inimai';

    /**
     * Megrim.
     */
    #[Label('Megrim')]
    #[Description('Megrim')]
    case MEGRIM = 'Megrim';

    /**
     * Meie Script.
     */
    #[Label('Meie Script')]
    #[Description('Meie Script')]
    case MEIE_SCRIPT = 'Meie Script';

    /**
     * Merienda.
     */
    #[Label('Merienda')]
    #[Description('Merienda')]
    case MERIENDA = 'Merienda';

    /**
     * Merienda One.
     */
    #[Label('Merienda One')]
    #[Description('Merienda One')]
    case MERIENDA_ONE = 'Merienda One';

    /**
     * Merriweather.
     */
    #[Label('Merriweather')]
    #[Description('Merriweather')]
    case MERRIWEATHER = 'Merriweather';

    /**
     * Merriweather Sans.
     */
    #[Label('Merriweather Sans')]
    #[Description('Merriweather Sans')]
    case MERRIWEATHER_SANS = 'Merriweather Sans';

    /**
     * Metal.
     */
    #[Label('Metal')]
    #[Description('Metal')]
    case METAL = 'Metal';

    /**
     * Metal Mania.
     */
    #[Label('Metal Mania')]
    #[Description('Metal Mania')]
    case METAL_MANIA = 'Metal Mania';

    /**
     * Metamorphous.
     */
    #[Label('Metamorphous')]
    #[Description('Metamorphous')]
    case METAMORPHOUS = 'Metamorphous';

    /**
     * Metrophobic.
     */
    #[Label('Metrophobic')]
    #[Description('Metrophobic')]
    case METROPHOBIC = 'Metrophobic';

    /**
     * Michroma.
     */
    #[Label('Michroma')]
    #[Description('Michroma')]
    case MICHROMA = 'Michroma';

    /**
     * Milonga.
     */
    #[Label('Milonga')]
    #[Description('Milonga')]
    case MILONGA = 'Milonga';

    /**
     * Miltonian.
     */
    #[Label('Miltonian')]
    #[Description('Miltonian')]
    case MILTONIAN = 'Miltonian';

    /**
     * Miltonian Tattoo.
     */
    #[Label('Miltonian Tattoo')]
    #[Description('Miltonian Tattoo')]
    case MILTONIAN_TATTOO = 'Miltonian Tattoo';

    /**
     * Miniver.
     */
    #[Label('Miniver')]
    #[Description('Miniver')]
    case MINIVER = 'Miniver';

    /**
     * Miriam Libre.
     */
    #[Label('Miriam Libre')]
    #[Description('Miriam Libre')]
    case MIRIAM_LIBRE = 'Miriam Libre';

    /**
     * Mirza.
     */
    #[Label('Mirza')]
    #[Description('Mirza')]
    case MIRZA = 'Mirza';

    /**
     * Miss Fajardose.
     */
    #[Label('Miss Fajardose')]
    #[Description('Miss Fajardose')]
    case MISS_FAJARDOSE = 'Miss Fajardose';

    /**
     * Mitr.
     */
    #[Label('Mitr')]
    #[Description('Mitr')]
    case MITR = 'Mitr';

    /**
     * Modak.
     */
    #[Label('Modak')]
    #[Description('Modak')]
    case MODAK = 'Modak';

    /**
     * Modern Antiqua.
     */
    #[Label('Modern Antiqua')]
    #[Description('Modern Antiqua')]
    case MODERN_ANTIQUA = 'Modern Antiqua';

    /**
     * Mogra.
     */
    #[Label('Mogra')]
    #[Description('Mogra')]
    case MOGRA = 'Mogra';

    /**
     * Molengo.
     */
    #[Label('Molengo')]
    #[Description('Molengo')]
    case MOLENGO = 'Molengo';

    /**
     * Molle.
     */
    #[Label('Molle')]
    #[Description('Molle')]
    case MOLLE = 'Molle';

    /**
     * Monda.
     */
    #[Label('Monda')]
    #[Description('Monda')]
    case MONDA = 'Monda';

    /**
     * Monofett.
     */
    #[Label('Monofett')]
    #[Description('Monofett')]
    case MONOFETT = 'Monofett';

    /**
     * Monoton.
     */
    #[Label('Monoton')]
    #[Description('Monoton')]
    case MONOTON = 'Monoton';

    /**
     * Monsieur La Doulaise.
     */
    #[Label('Monsieur La Doulaise')]
    #[Description('Monsieur La Doulaise')]
    case MONSIEUR_LA_DOULAISE = 'Monsieur La Doulaise';

    /**
     * Montaga.
     */
    #[Label('Montaga')]
    #[Description('Montaga')]
    case MONTAGA = 'Montaga';

    /**
     * Montez.
     */
    #[Label('Montez')]
    #[Description('Montez')]
    case MONTEZ = 'Montez';

    /**
     * Montserrat.
     */
    #[Label('Montserrat')]
    #[Description('Montserrat')]
    case MONTSERRAT = 'Montserrat';

    /**
     * Montserrat Alternates.
     */
    #[Label('Montserrat Alternates')]
    #[Description('Montserrat Alternates')]
    case MONTSERRAT_ALTERNATES = 'Montserrat Alternates';

    /**
     * Montserrat Subrayada.
     */
    #[Label('Montserrat Subrayada')]
    #[Description('Montserrat Subrayada')]
    case MONTSERRAT_SUBRAYADA = 'Montserrat Subrayada';

    /**
     * Moul.
     */
    #[Label('Moul')]
    #[Description('Moul')]
    case MOUL = 'Moul';

    /**
     * Moulpali.
     */
    #[Label('Moulpali')]
    #[Description('Moulpali')]
    case MOULPALI = 'Moulpali';

    /**
     * Mountains of Christmas.
     */
    #[Label('Mountains of Christmas')]
    #[Description('Mountains of Christmas')]
    case MOUNTAINS_OF_CHRISTMAS = 'Mountains of Christmas';

    /**
     * Mouse Memoirs.
     */
    #[Label('Mouse Memoirs')]
    #[Description('Mouse Memoirs')]
    case MOUSE_MEMOIRS = 'Mouse Memoirs';

    /**
     * Mr Bedfort.
     */
    #[Label('Mr Bedfort')]
    #[Description('Mr Bedfort')]
    case MR_BEDFORT = 'Mr Bedfort';

    /**
     * Mr Dafoe.
     */
    #[Label('Mr Dafoe')]
    #[Description('Mr Dafoe')]
    case MR_DAFOE = 'Mr Dafoe';

    /**
     * Mr De Haviland.
     */
    #[Label('Mr De Haviland')]
    #[Description('Mr De Haviland')]
    case MR_DE_HAVILAND = 'Mr De Haviland';

    /**
     * Mrs Saint Delafield.
     */
    #[Label('Mrs Saint Delafield')]
    #[Description('Mrs Saint Delafield')]
    case MRS_SAINT_DELAFIELD = 'Mrs Saint Delafield';

    /**
     * Mrs Sheppards.
     */
    #[Label('Mrs Sheppards')]
    #[Description('Mrs Sheppards')]
    case MRS_SHEPPARDS = 'Mrs Sheppards';

    /**
     * Mukta.
     */
    #[Label('Mukta')]
    #[Description('Mukta')]
    case MUKTA = 'Mukta';

    /**
     * Mukta Mahee.
     */
    #[Label('Mukta Mahee')]
    #[Description('Mukta Mahee')]
    case MUKTA_MAHEE = 'Mukta Mahee';

    /**
     * Mukta Malar.
     */
    #[Label('Mukta Malar')]
    #[Description('Mukta Malar')]
    case MUKTA_MALAR = 'Mukta Malar';

    /**
     * Mukta Vaani.
     */
    #[Label('Mukta Vaani')]
    #[Description('Mukta Vaani')]
    case MUKTA_VAANI = 'Mukta Vaani';

    /**
     * Muli.
     */
    #[Label('Muli')]
    #[Description('Muli')]
    case MULI = 'Muli';

    /**
     * Mystery Quest.
     */
    #[Label('Mystery Quest')]
    #[Description('Mystery Quest')]
    case MYSTERY_QUEST = 'Mystery Quest';

    /**
     * NTR.
     */
    #[Label('NTR')]
    #[Description('NTR')]
    case NTR = 'NTR';

    /**
     * Neucha.
     */
    #[Label('Neucha')]
    #[Description('Neucha')]
    case NEUCHA = 'Neucha';

    /**
     * Neuton.
     */
    #[Label('Neuton')]
    #[Description('Neuton')]
    case NEUTON = 'Neuton';

    /**
     * New Rocker.
     */
    #[Label('New Rocker')]
    #[Description('New Rocker')]
    case NEW_ROCKER = 'New Rocker';

    /**
     * News Cycle.
     */
    #[Label('News Cycle')]
    #[Description('News Cycle')]
    case NEWS_CYCLE = 'News Cycle';

    /**
     * Niconne.
     */
    #[Label('Niconne')]
    #[Description('Niconne')]
    case NICONNE = 'Niconne';

    /**
     * Nixie One.
     */
    #[Label('Nixie One')]
    #[Description('Nixie One')]
    case NIXIE_ONE = 'Nixie One';

    /**
     * Nobile.
     */
    #[Label('Nobile')]
    #[Description('Nobile')]
    case NOBILE = 'Nobile';

    /**
     * Nokora.
     */
    #[Label('Nokora')]
    #[Description('Nokora')]
    case NOKORA = 'Nokora';

    /**
     * Norican.
     */
    #[Label('Norican')]
    #[Description('Norican')]
    case NORICAN = 'Norican';

    /**
     * Nosifer.
     */
    #[Label('Nosifer')]
    #[Description('Nosifer')]
    case NOSIFER = 'Nosifer';

    /**
     * Nothing You Could Do.
     */
    #[Label('Nothing You Could Do')]
    #[Description('Nothing You Could Do')]
    case NOTHING_YOU_COULD_DO = 'Nothing You Could Do';

    /**
     * Noticia Text.
     */
    #[Label('Noticia Text')]
    #[Description('Noticia Text')]
    case NOTICIA_TEXT = 'Noticia Text';

    /**
     * Noto Sans.
     */
    #[Label('Noto Sans')]
    #[Description('Noto Sans')]
    case NOTO_SANS = 'Noto Sans';

    /**
     * Noto Serif.
     */
    #[Label('Noto Serif')]
    #[Description('Noto Serif')]
    case NOTO_SERIF = 'Noto Serif';

    /**
     * Nova Cut.
     */
    #[Label('Nova Cut')]
    #[Description('Nova Cut')]
    case NOVA_CUT = 'Nova Cut';

    /**
     * Nova Flat.
     */
    #[Label('Nova Flat')]
    #[Description('Nova Flat')]
    case NOVA_FLAT = 'Nova Flat';

    /**
     * Nova Mono.
     */
    #[Label('Nova Mono')]
    #[Description('Nova Mono')]
    case NOVA_MONO = 'Nova Mono';

    /**
     * Nova Oval.
     */
    #[Label('Nova Oval')]
    #[Description('Nova Oval')]
    case NOVA_OVAL = 'Nova Oval';

    /**
     * Nova Round.
     */
    #[Label('Nova Round')]
    #[Description('Nova Round')]
    case NOVA_ROUND = 'Nova Round';

    /**
     * Nova Script.
     */
    #[Label('Nova Script')]
    #[Description('Nova Script')]
    case NOVA_SCRIPT = 'Nova Script';

    /**
     * Nova Slim.
     */
    #[Label('Nova Slim')]
    #[Description('Nova Slim')]
    case NOVA_SLIM = 'Nova Slim';

    /**
     * Nova Square.
     */
    #[Label('Nova Square')]
    #[Description('Nova Square')]
    case NOVA_SQUARE = 'Nova Square';

    /**
     * Numans.
     */
    #[Label('Numans')]
    #[Description('Numans')]
    case NUMANS = 'Numans';

    /**
     * Nunito.
     */
    #[Label('Nunito')]
    #[Description('Nunito')]
    case NUNITO = 'Nunito';

    /**
     * Nunito Sans.
     */
    #[Label('Nunito Sans')]
    #[Description('Nunito Sans')]
    case NUNITO_SANS = 'Nunito Sans';

    /**
     * Odor Mean Chey.
     */
    #[Label('Odor Mean Chey')]
    #[Description('Odor Mean Chey')]
    case ODOR_MEAN_CHEY = 'Odor Mean Chey';

    /**
     * Offside.
     */
    #[Label('Offside')]
    #[Description('Offside')]
    case OFFSIDE = 'Offside';

    /**
     * Old Standard TT.
     */
    #[Label('Old Standard TT')]
    #[Description('Old Standard TT')]
    case OLD_STANDARD_TT = 'Old Standard TT';

    /**
     * Oldenburg.
     */
    #[Label('Oldenburg')]
    #[Description('Oldenburg')]
    case OLDENBURG = 'Oldenburg';

    /**
     * Oleo Script.
     */
    #[Label('Oleo Script')]
    #[Description('Oleo Script')]
    case OLEO_SCRIPT = 'Oleo Script';

    /**
     * Oleo Script Swash Caps.
     */
    #[Label('Oleo Script Swash Caps')]
    #[Description('Oleo Script Swash Caps')]
    case OLEO_SCRIPT_SWASH_CAPS = 'Oleo Script Swash Caps';

    /**
     * Open Sans.
     */
    #[Label('Open Sans')]
    #[Description('Open Sans')]
    case OPEN_SANS = 'Open Sans';

    /**
     * Open Sans Condensed.
     */
    #[Label('Open Sans Condensed')]
    #[Description('Open Sans Condensed')]
    case OPEN_SANS_CONDENSED = 'Open Sans Condensed';

    /**
     * Oranienbaum.
     */
    #[Label('Oranienbaum')]
    #[Description('Oranienbaum')]
    case ORANIENBAUM = 'Oranienbaum';

    /**
     * Orbitron.
     */
    #[Label('Orbitron')]
    #[Description('Orbitron')]
    case ORBITRON = 'Orbitron';

    /**
     * Oregano.
     */
    #[Label('Oregano')]
    #[Description('Oregano')]
    case OREGANO = 'Oregano';

    /**
     * Orienta.
     */
    #[Label('Orienta')]
    #[Description('Orienta')]
    case ORIENTA = 'Orienta';

    /**
     * Original Surfer.
     */
    #[Label('Original Surfer')]
    #[Description('Original Surfer')]
    case ORIGINAL_SURFER = 'Original Surfer';

    /**
     * Oswald.
     */
    #[Label('Oswald')]
    #[Description('Oswald')]
    case OSWALD = 'Oswald';

    /**
     * Over the Rainbow.
     */
    #[Label('Over the Rainbow')]
    #[Description('Over the Rainbow')]
    case OVER_THE_RAINBOW = 'Over the Rainbow';

    /**
     * Overlock.
     */
    #[Label('Overlock')]
    #[Description('Overlock')]
    case OVERLOCK = 'Overlock';

    /**
     * Overlock SC.
     */
    #[Label('Overlock SC')]
    #[Description('Overlock SC')]
    case OVERLOCK_SC = 'Overlock SC';

    /**
     * Overpass.
     */
    #[Label('Overpass')]
    #[Description('Overpass')]
    case OVERPASS = 'Overpass';

    /**
     * Overpass Mono.
     */
    #[Label('Overpass Mono')]
    #[Description('Overpass Mono')]
    case OVERPASS_MONO = 'Overpass Mono';

    /**
     * Ovo.
     */
    #[Label('Ovo')]
    #[Description('Ovo')]
    case OVO = 'Ovo';

    /**
     * Oxygen.
     */
    #[Label('Oxygen')]
    #[Description('Oxygen')]
    case OXYGEN = 'Oxygen';

    /**
     * Oxygen Mono.
     */
    #[Label('Oxygen Mono')]
    #[Description('Oxygen Mono')]
    case OXYGEN_MONO = 'Oxygen Mono';

    /**
     * PT Mono.
     */
    #[Label('PT Mono')]
    #[Description('PT Mono')]
    case PT_MONO = 'PT Mono';

    /**
     * PT Sans.
     */
    #[Label('PT Sans')]
    #[Description('PT Sans')]
    case PT_SANS = 'PT Sans';

    /**
     * PT Sans Caption.
     */
    #[Label('PT Sans Caption')]
    #[Description('PT Sans Caption')]
    case PT_SANS_CAPTION = 'PT Sans Caption';

    /**
     * PT Sans Narrow.
     */
    #[Label('PT Sans Narrow')]
    #[Description('PT Sans Narrow')]
    case PT_SANS_NARROW = 'PT Sans Narrow';

    /**
     * PT Serif.
     */
    #[Label('PT Serif')]
    #[Description('PT Serif')]
    case PT_SERIF = 'PT Serif';

    /**
     * PT Serif Caption.
     */
    #[Label('PT Serif Caption')]
    #[Description('PT Serif Caption')]
    case PT_SERIF_CAPTION = 'PT Serif Caption';

    /**
     * Pacifico.
     */
    #[Label('Pacifico')]
    #[Description('Pacifico')]
    case PACIFICO = 'Pacifico';

    /**
     * Padauk.
     */
    #[Label('Padauk')]
    #[Description('Padauk')]
    case PADAUK = 'Padauk';

    /**
     * Palanquin.
     */
    #[Label('Palanquin')]
    #[Description('Palanquin')]
    case PALANQUIN = 'Palanquin';

    /**
     * Palanquin Dark.
     */
    #[Label('Palanquin Dark')]
    #[Description('Palanquin Dark')]
    case PALANQUIN_DARK = 'Palanquin Dark';

    /**
     * Pangolin.
     */
    #[Label('Pangolin')]
    #[Description('Pangolin')]
    case PANGOLIN = 'Pangolin';

    /**
     * Paprika.
     */
    #[Label('Paprika')]
    #[Description('Paprika')]
    case PAPRIKA = 'Paprika';

    /**
     * Parisienne.
     */
    #[Label('Parisienne')]
    #[Description('Parisienne')]
    case PARISIENNE = 'Parisienne';

    /**
     * Passero One.
     */
    #[Label('Passero One')]
    #[Description('Passero One')]
    case PASSERO_ONE = 'Passero One';

    /**
     * Passion One.
     */
    #[Label('Passion One')]
    #[Description('Passion One')]
    case PASSION_ONE = 'Passion One';

    /**
     * Pathway Gothic One.
     */
    #[Label('Pathway Gothic One')]
    #[Description('Pathway Gothic One')]
    case PATHWAY_GOTHIC_ONE = 'Pathway Gothic One';

    /**
     * Patrick Hand.
     */
    #[Label('Patrick Hand')]
    #[Description('Patrick Hand')]
    case PATRICK_HAND = 'Patrick Hand';

    /**
     * Patrick Hand SC.
     */
    #[Label('Patrick Hand SC')]
    #[Description('Patrick Hand SC')]
    case PATRICK_HAND_SC = 'Patrick Hand SC';

    /**
     * Pattaya.
     */
    #[Label('Pattaya')]
    #[Description('Pattaya')]
    case PATTAYA = 'Pattaya';

    /**
     * Patua One.
     */
    #[Label('Patua One')]
    #[Description('Patua One')]
    case PATUA_ONE = 'Patua One';

    /**
     * Pavanam.
     */
    #[Label('Pavanam')]
    #[Description('Pavanam')]
    case PAVANAM = 'Pavanam';

    /**
     * Paytone One.
     */
    #[Label('Paytone One')]
    #[Description('Paytone One')]
    case PAYTONE_ONE = 'Paytone One';

    /**
     * Peddana.
     */
    #[Label('Peddana')]
    #[Description('Peddana')]
    case PEDDANA = 'Peddana';

    /**
     * Peralta.
     */
    #[Label('Peralta')]
    #[Description('Peralta')]
    case PERALTA = 'Peralta';

    /**
     * Permanent Marker.
     */
    #[Label('Permanent Marker')]
    #[Description('Permanent Marker')]
    case PERMANENT_MARKER = 'Permanent Marker';

    /**
     * Petit Formal Script.
     */
    #[Label('Petit Formal Script')]
    #[Description('Petit Formal Script')]
    case PETIT_FORMAL_SCRIPT = 'Petit Formal Script';

    /**
     * Petrona.
     */
    #[Label('Petrona')]
    #[Description('Petrona')]
    case PETRONA = 'Petrona';

    /**
     * Philosopher.
     */
    #[Label('Philosopher')]
    #[Description('Philosopher')]
    case PHILOSOPHER = 'Philosopher';

    /**
     * Piedra.
     */
    #[Label('Piedra')]
    #[Description('Piedra')]
    case PIEDRA = 'Piedra';

    /**
     * Pinyon Script.
     */
    #[Label('Pinyon Script')]
    #[Description('Pinyon Script')]
    case PINYON_SCRIPT = 'Pinyon Script';

    /**
     * Pirata One.
     */
    #[Label('Pirata One')]
    #[Description('Pirata One')]
    case PIRATA_ONE = 'Pirata One';

    /**
     * Plaster.
     */
    #[Label('Plaster')]
    #[Description('Plaster')]
    case PLASTER = 'Plaster';

    /**
     * Play.
     */
    #[Label('Play')]
    #[Description('Play')]
    case PLAY = 'Play';

    /**
     * Playball.
     */
    #[Label('Playball')]
    #[Description('Playball')]
    case PLAYBALL = 'Playball';

    /**
     * Playfair Display.
     */
    #[Label('Playfair Display')]
    #[Description('Playfair Display')]
    case PLAYFAIR_DISPLAY = 'Playfair Display';

    /**
     * Playfair Display SC.
     */
    #[Label('Playfair Display SC')]
    #[Description('Playfair Display SC')]
    case PLAYFAIR_DISPLAY_SC = 'Playfair Display SC';

    /**
     * Podkova.
     */
    #[Label('Podkova')]
    #[Description('Podkova')]
    case PODKOVA = 'Podkova';

    /**
     * Poiret One.
     */
    #[Label('Poiret One')]
    #[Description('Poiret One')]
    case POIRET_ONE = 'Poiret One';

    /**
     * Poller One.
     */
    #[Label('Poller One')]
    #[Description('Poller One')]
    case POLLER_ONE = 'Poller One';

    /**
     * Poly.
     */
    #[Label('Poly')]
    #[Description('Poly')]
    case POLY = 'Poly';

    /**
     * Pompiere.
     */
    #[Label('Pompiere')]
    #[Description('Pompiere')]
    case POMPIERE = 'Pompiere';

    /**
     * Pontano Sans.
     */
    #[Label('Pontano Sans')]
    #[Description('Pontano Sans')]
    case PONTANO_SANS = 'Pontano Sans';

    /**
     * Poppins.
     */
    #[Label('Poppins')]
    #[Description('Poppins')]
    case POPPINS = 'Poppins';

    /**
     * Port Lligat Sans.
     */
    #[Label('Port Lligat Sans')]
    #[Description('Port Lligat Sans')]
    case PORT_LLIGAT_SANS = 'Port Lligat Sans';

    /**
     * Port Lligat Slab.
     */
    #[Label('Port Lligat Slab')]
    #[Description('Port Lligat Slab')]
    case PORT_LLIGAT_SLAB = 'Port Lligat Slab';

    /**
     * Pragati Narrow.
     */
    #[Label('Pragati Narrow')]
    #[Description('Pragati Narrow')]
    case PRAGATI_NARROW = 'Pragati Narrow';

    /**
     * Prata.
     */
    #[Label('Prata')]
    #[Description('Prata')]
    case PRATA = 'Prata';

    /**
     * Preahvihear.
     */
    #[Label('Preahvihear')]
    #[Description('Preahvihear')]
    case PREAHVIHEAR = 'Preahvihear';

    /**
     * Press Start 2P.
     */
    #[Label('Press Start 2P')]
    #[Description('Press Start 2P')]
    case PRESS_START_2P = 'Press Start 2P';

    /**
     * Pridi.
     */
    #[Label('Pridi')]
    #[Description('Pridi')]
    case PRIDI = 'Pridi';

    /**
     * Princess Sofia.
     */
    #[Label('Princess Sofia')]
    #[Description('Princess Sofia')]
    case PRINCESS_SOFIA = 'Princess Sofia';

    /**
     * Prociono.
     */
    #[Label('Prociono')]
    #[Description('Prociono')]
    case PROCIONO = 'Prociono';

    /**
     * Prompt.
     */
    #[Label('Prompt')]
    #[Description('Prompt')]
    case PROMPT = 'Prompt';

    /**
     * Prosto One.
     */
    #[Label('Prosto One')]
    #[Description('Prosto One')]
    case PROSTO_ONE = 'Prosto One';

    /**
     * Proza Libre.
     */
    #[Label('Proza Libre')]
    #[Description('Proza Libre')]
    case PROZA_LIBRE = 'Proza Libre';

    /**
     * Puritan.
     */
    #[Label('Puritan')]
    #[Description('Puritan')]
    case PURITAN = 'Puritan';

    /**
     * Purple Purse.
     */
    #[Label('Purple Purse')]
    #[Description('Purple Purse')]
    case PURPLE_PURSE = 'Purple Purse';

    /**
     * Quando.
     */
    #[Label('Quando')]
    #[Description('Quando')]
    case QUANDO = 'Quando';

    /**
     * Quantico.
     */
    #[Label('Quantico')]
    #[Description('Quantico')]
    case QUANTICO = 'Quantico';

    /**
     * Quattrocento.
     */
    #[Label('Quattrocento')]
    #[Description('Quattrocento')]
    case QUATTROCENTO = 'Quattrocento';

    /**
     * Quattrocento Sans.
     */
    #[Label('Quattrocento Sans')]
    #[Description('Quattrocento Sans')]
    case QUATTROCENTO_SANS = 'Quattrocento Sans';

    /**
     * Questrial.
     */
    #[Label('Questrial')]
    #[Description('Questrial')]
    case QUESTRIAL = 'Questrial';

    /**
     * Quicksand.
     */
    #[Label('Quicksand')]
    #[Description('Quicksand')]
    case QUICKSAND = 'Quicksand';

    /**
     * Quintessential.
     */
    #[Label('Quintessential')]
    #[Description('Quintessential')]
    case QUINTESSENTIAL = 'Quintessential';

    /**
     * Qwigley.
     */
    #[Label('Qwigley')]
    #[Description('Qwigley')]
    case QWIGLEY = 'Qwigley';

    /**
     * Racing Sans One.
     */
    #[Label('Racing Sans One')]
    #[Description('Racing Sans One')]
    case RACING_SANS_ONE = 'Racing Sans One';

    /**
     * Radley.
     */
    #[Label('Radley')]
    #[Description('Radley')]
    case RADLEY = 'Radley';

    /**
     * Rajdhani.
     */
    #[Label('Rajdhani')]
    #[Description('Rajdhani')]
    case RAJDHANI = 'Rajdhani';

    /**
     * Rakkas.
     */
    #[Label('Rakkas')]
    #[Description('Rakkas')]
    case RAKKAS = 'Rakkas';

    /**
     * Raleway.
     */
    #[Label('Raleway')]
    #[Description('Raleway')]
    case RALEWAY = 'Raleway';

    /**
     * Raleway Dots.
     */
    #[Label('Raleway Dots')]
    #[Description('Raleway Dots')]
    case RALEWAY_DOTS = 'Raleway Dots';

    /**
     * Ramabhadra.
     */
    #[Label('Ramabhadra')]
    #[Description('Ramabhadra')]
    case RAMABHADRA = 'Ramabhadra';

    /**
     * Ramaraja.
     */
    #[Label('Ramaraja')]
    #[Description('Ramaraja')]
    case RAMARAJA = 'Ramaraja';

    /**
     * Rambla.
     */
    #[Label('Rambla')]
    #[Description('Rambla')]
    case RAMBLA = 'Rambla';

    /**
     * Rammetto One.
     */
    #[Label('Rammetto One')]
    #[Description('Rammetto One')]
    case RAMMETTO_ONE = 'Rammetto One';

    /**
     * Ranchers.
     */
    #[Label('Ranchers')]
    #[Description('Ranchers')]
    case RANCHERS = 'Ranchers';

    /**
     * Rancho.
     */
    #[Label('Rancho')]
    #[Description('Rancho')]
    case RANCHO = 'Rancho';

    /**
     * Ranga.
     */
    #[Label('Ranga')]
    #[Description('Ranga')]
    case RANGA = 'Ranga';

    /**
     * Rasa.
     */
    #[Label('Rasa')]
    #[Description('Rasa')]
    case RASA = 'Rasa';

    /**
     * Rationale.
     */
    #[Label('Rationale')]
    #[Description('Rationale')]
    case RATIONALE = 'Rationale';

    /**
     * Ravi Prakash.
     */
    #[Label('Ravi Prakash')]
    #[Description('Ravi Prakash')]
    case RAVI_PRAKASH = 'Ravi Prakash';

    /**
     * Redressed.
     */
    #[Label('Redressed')]
    #[Description('Redressed')]
    case REDRESSED = 'Redressed';

    /**
     * Reem Kufi.
     */
    #[Label('Reem Kufi')]
    #[Description('Reem Kufi')]
    case REEM_KUFI = 'Reem Kufi';

    /**
     * Reenie Beanie.
     */
    #[Label('Reenie Beanie')]
    #[Description('Reenie Beanie')]
    case REENIE_BEANIE = 'Reenie Beanie';

    /**
     * Revalia.
     */
    #[Label('Revalia')]
    #[Description('Revalia')]
    case REVALIA = 'Revalia';

    /**
     * Rhodium Libre.
     */
    #[Label('Rhodium Libre')]
    #[Description('Rhodium Libre')]
    case RHODIUM_LIBRE = 'Rhodium Libre';

    /**
     * Ribeye.
     */
    #[Label('Ribeye')]
    #[Description('Ribeye')]
    case RIBEYE = 'Ribeye';

    /**
     * Ribeye Marrow.
     */
    #[Label('Ribeye Marrow')]
    #[Description('Ribeye Marrow')]
    case RIBEYE_MARROW = 'Ribeye Marrow';

    /**
     * Righteous.
     */
    #[Label('Righteous')]
    #[Description('Righteous')]
    case RIGHTEOUS = 'Righteous';

    /**
     * Risque.
     */
    #[Label('Risque')]
    #[Description('Risque')]
    case RISQUE = 'Risque';

    /**
     * Roboto.
     */
    #[Label('Roboto')]
    #[Description('Roboto')]
    case ROBOTO = 'Roboto';

    /**
     * Roboto Condensed.
     */
    #[Label('Roboto Condensed')]
    #[Description('Roboto Condensed')]
    case ROBOTO_CONDENSED = 'Roboto Condensed';

    /**
     * Roboto Mono.
     */
    #[Label('Roboto Mono')]
    #[Description('Roboto Mono')]
    case ROBOTO_MONO = 'Roboto Mono';

    /**
     * Roboto Slab.
     */
    #[Label('Roboto Slab')]
    #[Description('Roboto Slab')]
    case ROBOTO_SLAB = 'Roboto Slab';

    /**
     * Rochester.
     */
    #[Label('Rochester')]
    #[Description('Rochester')]
    case ROCHESTER = 'Rochester';

    /**
     * Rock Salt.
     */
    #[Label('Rock Salt')]
    #[Description('Rock Salt')]
    case ROCK_SALT = 'Rock Salt';

    /**
     * Rokkitt.
     */
    #[Label('Rokkitt')]
    #[Description('Rokkitt')]
    case ROKKITT = 'Rokkitt';

    /**
     * Romanesco.
     */
    #[Label('Romanesco')]
    #[Description('Romanesco')]
    case ROMANESCO = 'Romanesco';

    /**
     * Ropa Sans.
     */
    #[Label('Ropa Sans')]
    #[Description('Ropa Sans')]
    case ROPA_SANS = 'Ropa Sans';

    /**
     * Rosario.
     */
    #[Label('Rosario')]
    #[Description('Rosario')]
    case ROSARIO = 'Rosario';

    /**
     * Rosarivo.
     */
    #[Label('Rosarivo')]
    #[Description('Rosarivo')]
    case ROSARIVO = 'Rosarivo';

    /**
     * Rouge Script.
     */
    #[Label('Rouge Script')]
    #[Description('Rouge Script')]
    case ROUGE_SCRIPT = 'Rouge Script';

    /**
     * Rozha One.
     */
    #[Label('Rozha One')]
    #[Description('Rozha One')]
    case ROZHA_ONE = 'Rozha One';

    /**
     * Rubik.
     */
    #[Label('Rubik')]
    #[Description('Rubik')]
    case RUBIK = 'Rubik';

    /**
     * Rubik Mono One.
     */
    #[Label('Rubik Mono One')]
    #[Description('Rubik Mono One')]
    case RUBIK_MONO_ONE = 'Rubik Mono One';

    /**
     * Ruda.
     */
    #[Label('Ruda')]
    #[Description('Ruda')]
    case RUDA = 'Ruda';

    /**
     * Rufina.
     */
    #[Label('Rufina')]
    #[Description('Rufina')]
    case RUFINA = 'Rufina';

    /**
     * Ruge Boogie.
     */
    #[Label('Ruge Boogie')]
    #[Description('Ruge Boogie')]
    case RUGE_BOOGIE = 'Ruge Boogie';

    /**
     * Ruluko.
     */
    #[Label('Ruluko')]
    #[Description('Ruluko')]
    case RULUKO = 'Ruluko';

    /**
     * Rum Raisin.
     */
    #[Label('Rum Raisin')]
    #[Description('Rum Raisin')]
    case RUM_RAISIN = 'Rum Raisin';

    /**
     * Ruslan Display.
     */
    #[Label('Ruslan Display')]
    #[Description('Ruslan Display')]
    case RUSLAN_DISPLAY = 'Ruslan Display';

    /**
     * Russo One.
     */
    #[Label('Russo One')]
    #[Description('Russo One')]
    case RUSSO_ONE = 'Russo One';

    /**
     * Ruthie.
     */
    #[Label('Ruthie')]
    #[Description('Ruthie')]
    case RUTHIE = 'Ruthie';

    /**
     * Rye.
     */
    #[Label('Rye')]
    #[Description('Rye')]
    case RYE = 'Rye';

    /**
     * Sacramento.
     */
    #[Label('Sacramento')]
    #[Description('Sacramento')]
    case SACRAMENTO = 'Sacramento';

    /**
     * Sahitya.
     */
    #[Label('Sahitya')]
    #[Description('Sahitya')]
    case SAHITYA = 'Sahitya';

    /**
     * Sail.
     */
    #[Label('Sail')]
    #[Description('Sail')]
    case SAIL = 'Sail';

    /**
     * Saira.
     */
    #[Label('Saira')]
    #[Description('Saira')]
    case SAIRA = 'Saira';

    /**
     * Saira Condensed.
     */
    #[Label('Saira Condensed')]
    #[Description('Saira Condensed')]
    case SAIRA_CONDENSED = 'Saira Condensed';

    /**
     * Saira Extra Condensed.
     */
    #[Label('Saira Extra Condensed')]
    #[Description('Saira Extra Condensed')]
    case SAIRA_EXTRA_CONDENSED = 'Saira Extra Condensed';

    /**
     * Saira Semi Condensed.
     */
    #[Label('Saira Semi Condensed')]
    #[Description('Saira Semi Condensed')]
    case SAIRA_SEMI_CONDENSED = 'Saira Semi Condensed';

    /**
     * Salsa.
     */
    #[Label('Salsa')]
    #[Description('Salsa')]
    case SALSA = 'Salsa';

    /**
     * Sanchez.
     */
    #[Label('Sanchez')]
    #[Description('Sanchez')]
    case SANCHEZ = 'Sanchez';

    /**
     * Sancreek.
     */
    #[Label('Sancreek')]
    #[Description('Sancreek')]
    case SANCREEK = 'Sancreek';

    /**
     * Sansita.
     */
    #[Label('Sansita')]
    #[Description('Sansita')]
    case SANSITA = 'Sansita';

    /**
     * Sarala.
     */
    #[Label('Sarala')]
    #[Description('Sarala')]
    case SARALA = 'Sarala';

    /**
     * Sarina.
     */
    #[Label('Sarina')]
    #[Description('Sarina')]
    case SARINA = 'Sarina';

    /**
     * Sarpanch.
     */
    #[Label('Sarpanch')]
    #[Description('Sarpanch')]
    case SARPANCH = 'Sarpanch';

    /**
     * Satisfy.
     */
    #[Label('Satisfy')]
    #[Description('Satisfy')]
    case SATISFY = 'Satisfy';

    /**
     * Scada.
     */
    #[Label('Scada')]
    #[Description('Scada')]
    case SCADA = 'Scada';

    /**
     * Scheherazade.
     */
    #[Label('Scheherazade')]
    #[Description('Scheherazade')]
    case SCHEHERAZADE = 'Scheherazade';

    /**
     * Schoolbell.
     */
    #[Label('Schoolbell')]
    #[Description('Schoolbell')]
    case SCHOOLBELL = 'Schoolbell';

    /**
     * Scope One.
     */
    #[Label('Scope One')]
    #[Description('Scope One')]
    case SCOPE_ONE = 'Scope One';

    /**
     * Seaweed Script.
     */
    #[Label('Seaweed Script')]
    #[Description('Seaweed Script')]
    case SEAWEED_SCRIPT = 'Seaweed Script';

    /**
     * Secular One.
     */
    #[Label('Secular One')]
    #[Description('Secular One')]
    case SECULAR_ONE = 'Secular One';

    /**
     * Sedgwick Ave.
     */
    #[Label('Sedgwick Ave')]
    #[Description('Sedgwick Ave')]
    case SEDGWICK_AVE = 'Sedgwick Ave';

    /**
     * Sedgwick Ave Display.
     */
    #[Label('Sedgwick Ave Display')]
    #[Description('Sedgwick Ave Display')]
    case SEDGWICK_AVE_DISPLAY = 'Sedgwick Ave Display';

    /**
     * Sevillana.
     */
    #[Label('Sevillana')]
    #[Description('Sevillana')]
    case SEVILLANA = 'Sevillana';

    /**
     * Seymour One.
     */
    #[Label('Seymour One')]
    #[Description('Seymour One')]
    case SEYMOUR_ONE = 'Seymour One';

    /**
     * Shadows Into Light.
     */
    #[Label('Shadows Into Light')]
    #[Description('Shadows Into Light')]
    case SHADOWS_INTO_LIGHT = 'Shadows Into Light';

    /**
     * Shadows Into Light Two.
     */
    #[Label('Shadows Into Light Two')]
    #[Description('Shadows Into Light Two')]
    case SHADOWS_INTO_LIGHT_TWO = 'Shadows Into Light Two';

    /**
     * Shanti.
     */
    #[Label('Shanti')]
    #[Description('Shanti')]
    case SHANTI = 'Shanti';

    /**
     * Share.
     */
    #[Label('Share')]
    #[Description('Share')]
    case SHARE = 'Share';

    /**
     * Share Tech.
     */
    #[Label('Share Tech')]
    #[Description('Share Tech')]
    case SHARE_TECH = 'Share Tech';

    /**
     * Share Tech Mono.
     */
    #[Label('Share Tech Mono')]
    #[Description('Share Tech Mono')]
    case SHARE_TECH_MONO = 'Share Tech Mono';

    /**
     * Shojumaru.
     */
    #[Label('Shojumaru')]
    #[Description('Shojumaru')]
    case SHOJUMARU = 'Shojumaru';

    /**
     * Short Stack.
     */
    #[Label('Short Stack')]
    #[Description('Short Stack')]
    case SHORT_STACK = 'Short Stack';

    /**
     * Shrikhand.
     */
    #[Label('Shrikhand')]
    #[Description('Shrikhand')]
    case SHRIKHAND = 'Shrikhand';

    /**
     * Siemreap.
     */
    #[Label('Siemreap')]
    #[Description('Siemreap')]
    case SIEMREAP = 'Siemreap';

    /**
     * Sigmar One.
     */
    #[Label('Sigmar One')]
    #[Description('Sigmar One')]
    case SIGMAR_ONE = 'Sigmar One';

    /**
     * Signika.
     */
    #[Label('Signika')]
    #[Description('Signika')]
    case SIGNIKA = 'Signika';

    /**
     * Signika Negative.
     */
    #[Label('Signika Negative')]
    #[Description('Signika Negative')]
    case SIGNIKA_NEGATIVE = 'Signika Negative';

    /**
     * Simonetta.
     */
    #[Label('Simonetta')]
    #[Description('Simonetta')]
    case SIMONETTA = 'Simonetta';

    /**
     * Sintony.
     */
    #[Label('Sintony')]
    #[Description('Sintony')]
    case SINTONY = 'Sintony';

    /**
     * Sirin Stencil.
     */
    #[Label('Sirin Stencil')]
    #[Description('Sirin Stencil')]
    case SIRIN_STENCIL = 'Sirin Stencil';

    /**
     * Six Caps.
     */
    #[Label('Six Caps')]
    #[Description('Six Caps')]
    case SIX_CAPS = 'Six Caps';

    /**
     * Skranji.
     */
    #[Label('Skranji')]
    #[Description('Skranji')]
    case SKRANJI = 'Skranji';

    /**
     * Slabo 13px.
     */
    #[Label('Slabo 13px')]
    #[Description('Slabo 13px')]
    case SLABO_13PX = 'Slabo 13px';

    /**
     * Slabo 27px.
     */
    #[Label('Slabo 27px')]
    #[Description('Slabo 27px')]
    case SLABO_27PX = 'Slabo 27px';

    /**
     * Slackey.
     */
    #[Label('Slackey')]
    #[Description('Slackey')]
    case SLACKEY = 'Slackey';

    /**
     * Smokum.
     */
    #[Label('Smokum')]
    #[Description('Smokum')]
    case SMOKUM = 'Smokum';

    /**
     * Smythe.
     */
    #[Label('Smythe')]
    #[Description('Smythe')]
    case SMYTHE = 'Smythe';

    /**
     * Sniglet.
     */
    #[Label('Sniglet')]
    #[Description('Sniglet')]
    case SNIGLET = 'Sniglet';

    /**
     * Snippet.
     */
    #[Label('Snippet')]
    #[Description('Snippet')]
    case SNIPPET = 'Snippet';

    /**
     * Snowburst One.
     */
    #[Label('Snowburst One')]
    #[Description('Snowburst One')]
    case SNOWBURST_ONE = 'Snowburst One';

    /**
     * Sofadi One.
     */
    #[Label('Sofadi One')]
    #[Description('Sofadi One')]
    case SOFADI_ONE = 'Sofadi One';

    /**
     * Sofia.
     */
    #[Label('Sofia')]
    #[Description('Sofia')]
    case SOFIA = 'Sofia';

    /**
     * Sonsie One.
     */
    #[Label('Sonsie One')]
    #[Description('Sonsie One')]
    case SONSIE_ONE = 'Sonsie One';

    /**
     * Sorts Mill Goudy.
     */
    #[Label('Sorts Mill Goudy')]
    #[Description('Sorts Mill Goudy')]
    case SORTS_MILL_GOUDY = 'Sorts Mill Goudy';

    /**
     * Source Code Pro.
     */
    #[Label('Source Code Pro')]
    #[Description('Source Code Pro')]
    case SOURCE_CODE_PRO = 'Source Code Pro';

    /**
     * Source Sans Pro.
     */
    #[Label('Source Sans Pro')]
    #[Description('Source Sans Pro')]
    case SOURCE_SANS_PRO = 'Source Sans Pro';

    /**
     * Source Serif Pro.
     */
    #[Label('Source Serif Pro')]
    #[Description('Source Serif Pro')]
    case SOURCE_SERIF_PRO = 'Source Serif Pro';

    /**
     * Space Mono.
     */
    #[Label('Space Mono')]
    #[Description('Space Mono')]
    case SPACE_MONO = 'Space Mono';

    /**
     * Special Elite.
     */
    #[Label('Special Elite')]
    #[Description('Special Elite')]
    case SPECIAL_ELITE = 'Special Elite';

    /**
     * Spectral.
     */
    #[Label('Spectral')]
    #[Description('Spectral')]
    case SPECTRAL = 'Spectral';

    /**
     * Spectral SC.
     */
    #[Label('Spectral SC')]
    #[Description('Spectral SC')]
    case SPECTRAL_SC = 'Spectral SC';

    /**
     * Spicy Rice.
     */
    #[Label('Spicy Rice')]
    #[Description('Spicy Rice')]
    case SPICY_RICE = 'Spicy Rice';

    /**
     * Spinnaker.
     */
    #[Label('Spinnaker')]
    #[Description('Spinnaker')]
    case SPINNAKER = 'Spinnaker';

    /**
     * Spirax.
     */
    #[Label('Spirax')]
    #[Description('Spirax')]
    case SPIRAX = 'Spirax';

    /**
     * Squada One.
     */
    #[Label('Squada One')]
    #[Description('Squada One')]
    case SQUADA_ONE = 'Squada One';

    /**
     * Sree Krushnadevaraya.
     */
    #[Label('Sree Krushnadevaraya')]
    #[Description('Sree Krushnadevaraya')]
    case SREE_KRUSHNADEVARAYA = 'Sree Krushnadevaraya';

    /**
     * Sriracha.
     */
    #[Label('Sriracha')]
    #[Description('Sriracha')]
    case SRIRACHA = 'Sriracha';

    /**
     * Stalemate.
     */
    #[Label('Stalemate')]
    #[Description('Stalemate')]
    case STALEMATE = 'Stalemate';

    /**
     * Stalinist One.
     */
    #[Label('Stalinist One')]
    #[Description('Stalinist One')]
    case STALINIST_ONE = 'Stalinist One';

    /**
     * Stardos Stencil.
     */
    #[Label('Stardos Stencil')]
    #[Description('Stardos Stencil')]
    case STARDOS_STENCIL = 'Stardos Stencil';

    /**
     * Stint Ultra Condensed.
     */
    #[Label('Stint Ultra Condensed')]
    #[Description('Stint Ultra Condensed')]
    case STINT_ULTRA_CONDENSED = 'Stint Ultra Condensed';

    /**
     * Stint Ultra Expanded.
     */
    #[Label('Stint Ultra Expanded')]
    #[Description('Stint Ultra Expanded')]
    case STINT_ULTRA_EXPANDED = 'Stint Ultra Expanded';

    /**
     * Stoke.
     */
    #[Label('Stoke')]
    #[Description('Stoke')]
    case STOKE = 'Stoke';

    /**
     * Strait.
     */
    #[Label('Strait')]
    #[Description('Strait')]
    case STRAIT = 'Strait';

    /**
     * Sue Ellen Francisco.
     */
    #[Label('Sue Ellen Francisco')]
    #[Description('Sue Ellen Francisco')]
    case SUE_ELLEN_FRANCISCO = 'Sue Ellen Francisco';

    /**
     * Suez One.
     */
    #[Label('Suez One')]
    #[Description('Suez One')]
    case SUEZ_ONE = 'Suez One';

    /**
     * Sumana.
     */
    #[Label('Sumana')]
    #[Description('Sumana')]
    case SUMANA = 'Sumana';

    /**
     * Sunshiney.
     */
    #[Label('Sunshiney')]
    #[Description('Sunshiney')]
    case SUNSHINEY = 'Sunshiney';

    /**
     * Supermercado One.
     */
    #[Label('Supermercado One')]
    #[Description('Supermercado One')]
    case SUPERMERCADO_ONE = 'Supermercado One';

    /**
     * Sura.
     */
    #[Label('Sura')]
    #[Description('Sura')]
    case SURA = 'Sura';

    /**
     * Suranna.
     */
    #[Label('Suranna')]
    #[Description('Suranna')]
    case SURANNA = 'Suranna';

    /**
     * Suravaram.
     */
    #[Label('Suravaram')]
    #[Description('Suravaram')]
    case SURAVARAM = 'Suravaram';

    /**
     * Suwannaphum.
     */
    #[Label('Suwannaphum')]
    #[Description('Suwannaphum')]
    case SUWANNAPHUM = 'Suwannaphum';

    /**
     * Swanky and Moo Moo.
     */
    #[Label('Swanky and Moo Moo')]
    #[Description('Swanky and Moo Moo')]
    case SWANKY_AND_MOO_MOO = 'Swanky and Moo Moo';

    /**
     * Syncopate.
     */
    #[Label('Syncopate')]
    #[Description('Syncopate')]
    case SYNCOPATE = 'Syncopate';

    /**
     * Tangerine.
     */
    #[Label('Tangerine')]
    #[Description('Tangerine')]
    case TANGERINE = 'Tangerine';

    /**
     * Taprom.
     */
    #[Label('Taprom')]
    #[Description('Taprom')]
    case TAPROM = 'Taprom';

    /**
     * Tauri.
     */
    #[Label('Tauri')]
    #[Description('Tauri')]
    case TAURI = 'Tauri';

    /**
     * Taviraj.
     */
    #[Label('Taviraj')]
    #[Description('Taviraj')]
    case TAVIRAJ = 'Taviraj';

    /**
     * Teko.
     */
    #[Label('Teko')]
    #[Description('Teko')]
    case TEKO = 'Teko';

    /**
     * Telex.
     */
    #[Label('Telex')]
    #[Description('Telex')]
    case TELEX = 'Telex';

    /**
     * Tenali Ramakrishna.
     */
    #[Label('Tenali Ramakrishna')]
    #[Description('Tenali Ramakrishna')]
    case TENALI_RAMAKRISHNA = 'Tenali Ramakrishna';

    /**
     * Tenor Sans.
     */
    #[Label('Tenor Sans')]
    #[Description('Tenor Sans')]
    case TENOR_SANS = 'Tenor Sans';

    /**
     * Text Me One.
     */
    #[Label('Text Me One')]
    #[Description('Text Me One')]
    case TEXT_ME_ONE = 'Text Me One';

    /**
     * The Girl Next Door.
     */
    #[Label('The Girl Next Door')]
    #[Description('The Girl Next Door')]
    case THE_GIRL_NEXT_DOOR = 'The Girl Next Door';

    /**
     * Tienne.
     */
    #[Label('Tienne')]
    #[Description('Tienne')]
    case TIENNE = 'Tienne';

    /**
     * Tillana.
     */
    #[Label('Tillana')]
    #[Description('Tillana')]
    case TILLANA = 'Tillana';

    /**
     * Timmana.
     */
    #[Label('Timmana')]
    #[Description('Timmana')]
    case TIMMANA = 'Timmana';

    /**
     * Tinos.
     */
    #[Label('Tinos')]
    #[Description('Tinos')]
    case TINOS = 'Tinos';

    /**
     * Titan One.
     */
    #[Label('Titan One')]
    #[Description('Titan One')]
    case TITAN_ONE = 'Titan One';

    /**
     * Titillium Web.
     */
    #[Label('Titillium Web')]
    #[Description('Titillium Web')]
    case TITILLIUM_WEB = 'Titillium Web';

    /**
     * Trade Winds.
     */
    #[Label('Trade Winds')]
    #[Description('Trade Winds')]
    case TRADE_WINDS = 'Trade Winds';

    /**
     * Trirong.
     */
    #[Label('Trirong')]
    #[Description('Trirong')]
    case TRIRONG = 'Trirong';

    /**
     * Trocchi.
     */
    #[Label('Trocchi')]
    #[Description('Trocchi')]
    case TROCCHI = 'Trocchi';

    /**
     * Trochut.
     */
    #[Label('Trochut')]
    #[Description('Trochut')]
    case TROCHUT = 'Trochut';

    /**
     * Trykker.
     */
    #[Label('Trykker')]
    #[Description('Trykker')]
    case TRYKKER = 'Trykker';

    /**
     * Tulpen One.
     */
    #[Label('Tulpen One')]
    #[Description('Tulpen One')]
    case TULPEN_ONE = 'Tulpen One';

    /**
     * Ubuntu.
     */
    #[Label('Ubuntu')]
    #[Description('Ubuntu')]
    case UBUNTU = 'Ubuntu';

    /**
     * Ubuntu Condensed.
     */
    #[Label('Ubuntu Condensed')]
    #[Description('Ubuntu Condensed')]
    case UBUNTU_CONDENSED = 'Ubuntu Condensed';

    /**
     * Ubuntu Mono.
     */
    #[Label('Ubuntu Mono')]
    #[Description('Ubuntu Mono')]
    case UBUNTU_MONO = 'Ubuntu Mono';

    /**
     * Ultra.
     */
    #[Label('Ultra')]
    #[Description('Ultra')]
    case ULTRA = 'Ultra';

    /**
     * Uncial Antiqua.
     */
    #[Label('Uncial Antiqua')]
    #[Description('Uncial Antiqua')]
    case UNCIAL_ANTIQUA = 'Uncial Antiqua';

    /**
     * Underdog.
     */
    #[Label('Underdog')]
    #[Description('Underdog')]
    case UNDERDOG = 'Underdog';

    /**
     * Unica One.
     */
    #[Label('Unica One')]
    #[Description('Unica One')]
    case UNICA_ONE = 'Unica One';

    /**
     * UnifrakturCook.
     */
    #[Label('UnifrakturCook')]
    #[Description('UnifrakturCook')]
    case UNIFRAKTURCOOK = 'UnifrakturCook';

    /**
     * UnifrakturMaguntia.
     */
    #[Label('UnifrakturMaguntia')]
    #[Description('UnifrakturMaguntia')]
    case UNIFRAKTURMAGUNTIA = 'UnifrakturMaguntia';

    /**
     * Unkempt.
     */
    #[Label('Unkempt')]
    #[Description('Unkempt')]
    case UNKEMPT = 'Unkempt';

    /**
     * Unlock.
     */
    #[Label('Unlock')]
    #[Description('Unlock')]
    case UNLOCK = 'Unlock';

    /**
     * Unna.
     */
    #[Label('Unna')]
    #[Description('Unna')]
    case UNNA = 'Unna';

    /**
     * VT323.
     */
    #[Label('VT323')]
    #[Description('VT323')]
    case VT323 = 'VT323';

    /**
     * Vampiro One.
     */
    #[Label('Vampiro One')]
    #[Description('Vampiro One')]
    case VAMPIRO_ONE = 'Vampiro One';

    /**
     * Varela.
     */
    #[Label('Varela')]
    #[Description('Varela')]
    case VARELA = 'Varela';

    /**
     * Varela Round.
     */
    #[Label('Varela Round')]
    #[Description('Varela Round')]
    case VARELA_ROUND = 'Varela Round';

    /**
     * Vast Shadow.
     */
    #[Label('Vast Shadow')]
    #[Description('Vast Shadow')]
    case VAST_SHADOW = 'Vast Shadow';

    /**
     * Vesper Libre.
     */
    #[Label('Vesper Libre')]
    #[Description('Vesper Libre')]
    case VESPER_LIBRE = 'Vesper Libre';

    /**
     * Vibur.
     */
    #[Label('Vibur')]
    #[Description('Vibur')]
    case VIBUR = 'Vibur';

    /**
     * Vidaloka.
     */
    #[Label('Vidaloka')]
    #[Description('Vidaloka')]
    case VIDALOKA = 'Vidaloka';

    /**
     * Viga.
     */
    #[Label('Viga')]
    #[Description('Viga')]
    case VIGA = 'Viga';

    /**
     * Voces.
     */
    #[Label('Voces')]
    #[Description('Voces')]
    case VOCES = 'Voces';

    /**
     * Volkhov.
     */
    #[Label('Volkhov')]
    #[Description('Volkhov')]
    case VOLKHOV = 'Volkhov';

    /**
     * Vollkorn.
     */
    #[Label('Vollkorn')]
    #[Description('Vollkorn')]
    case VOLLKORN = 'Vollkorn';

    /**
     * Vollkorn SC.
     */
    #[Label('Vollkorn SC')]
    #[Description('Vollkorn SC')]
    case VOLLKORN_SC = 'Vollkorn SC';

    /**
     * Voltaire.
     */
    #[Label('Voltaire')]
    #[Description('Voltaire')]
    case VOLTAIRE = 'Voltaire';

    /**
     * Waiting for the Sunrise.
     */
    #[Label('Waiting for the Sunrise')]
    #[Description('Waiting for the Sunrise')]
    case WAITING_FOR_THE_SUNRISE = 'Waiting for the Sunrise';

    /**
     * Wallpoet.
     */
    #[Label('Wallpoet')]
    #[Description('Wallpoet')]
    case WALLPOET = 'Wallpoet';

    /**
     * Walter Turncoat.
     */
    #[Label('Walter Turncoat')]
    #[Description('Walter Turncoat')]
    case WALTER_TURNCOAT = 'Walter Turncoat';

    /**
     * Warnes.
     */
    #[Label('Warnes')]
    #[Description('Warnes')]
    case WARNES = 'Warnes';

    /**
     * Wellfleet.
     */
    #[Label('Wellfleet')]
    #[Description('Wellfleet')]
    case WELLFLEET = 'Wellfleet';

    /**
     * Wendy One.
     */
    #[Label('Wendy One')]
    #[Description('Wendy One')]
    case WENDY_ONE = 'Wendy One';

    /**
     * Wire One.
     */
    #[Label('Wire One')]
    #[Description('Wire One')]
    case WIRE_ONE = 'Wire One';

    /**
     * Work Sans.
     */
    #[Label('Work Sans')]
    #[Description('Work Sans')]
    case WORK_SANS = 'Work Sans';

    /**
     * Yanone Kaffeesatz.
     */
    #[Label('Yanone Kaffeesatz')]
    #[Description('Yanone Kaffeesatz')]
    case YANONE_KAFFEESATZ = 'Yanone Kaffeesatz';

    /**
     * Yantramanav.
     */
    #[Label('Yantramanav')]
    #[Description('Yantramanav')]
    case YANTRAMANAV = 'Yantramanav';

    /**
     * Yatra One.
     */
    #[Label('Yatra One')]
    #[Description('Yatra One')]
    case YATRA_ONE = 'Yatra One';

    /**
     * Yellowtail.
     */
    #[Label('Yellowtail')]
    #[Description('Yellowtail')]
    case YELLOWTAIL = 'Yellowtail';

    /**
     * Yeseva One.
     */
    #[Label('Yeseva One')]
    #[Description('Yeseva One')]
    case YESEVA_ONE = 'Yeseva One';

    /**
     * Yesteryear.
     */
    #[Label('Yesteryear')]
    #[Description('Yesteryear')]
    case YESTERYEAR = 'Yesteryear';

    /**
     * Yrsa.
     */
    #[Label('Yrsa')]
    #[Description('Yrsa')]
    case YRSA = 'Yrsa';

    /**
     * Zeyada.
     */
    #[Label('Zeyada')]
    #[Description('Zeyada')]
    case ZEYADA = 'Zeyada';

    /**
     * Zilla Slab.
     */
    #[Label('Zilla Slab')]
    #[Description('Zilla Slab')]
    case ZILLA_SLAB = 'Zilla Slab';

    /**
     * Zilla Slab Highlight.
     */
    #[Label('Zilla Slab Highlight')]
    #[Description('Zilla Slab Highlight')]
    case ZILLA_SLAB_HIGHLIGHT = 'Zilla Slab Highlight';
}
