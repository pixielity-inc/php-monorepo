<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum for emojis.
 *
 * This enum defines various emojis.
 *
 * @method static THUMBS_UP() Returns the THUMBS_UP enum instance
 * @method static THUMBS_DOWN() Returns the THUMBS_DOWN enum instance
 * @method static SMILING_FACE() Returns the SMILING_FACE enum instance
 * @method static CRYING_FACE() Returns the CRYING_FACE enum instance
 * @method static HEART() Returns the HEART enum instance
 * @method static STAR() Returns the STAR enum instance
 * @method static FIRE() Returns the FIRE enum instance
 * @method static CLAPPING_HANDS() Returns the CLAPPING_HANDS enum instance
 * @method static PARTY_POPPER() Returns the PARTY_POPPER enum instance
 * @method static CHECK_MARK() Returns the CHECK_MARK enum instance
 * @method static QUESTION_MARK() Returns the QUESTION_MARK enum instance
 * @method static EXCLAMATION_MARK() Returns the EXCLAMATION_MARK enum instance
 * @method static LAUGHING_FACE() Returns the LAUGHING_FACE enum instance
 * @method static WINKING_FACE() Returns the WINKING_FACE enum instance
 * @method static ANGRY_FACE() Returns the ANGRY_FACE enum instance
 * @method static CONFUSED_FACE() Returns the CONFUSED_FACE enum instance
 * @method static SMILING_FACE_WITH_SUNGGLASSES() Returns the SMILING_FACE_WITH_SUNGGLASSES enum instance
 * @method static THINKING_FACE() Returns the THINKING_FACE enum instance
 * @method static HUGGING_FACE() Returns the HUGGING_FACE enum instance
 * @method static KISSING_FACE() Returns the KISSING_FACE enum instance
 * @method static ASTONISHED_FACE() Returns the ASTONISHED_FACE enum instance
 * @method static PARTYING_FACE() Returns the PARTYING_FACE enum instance
 * @method static ROBOT_FACE() Returns the ROBOT_FACE enum instance
 * @method static GRINNING_CAT_FACE() Returns the GRINNING_CAT_FACE enum instance
 * @method static DOG_FACE() Returns the DOG_FACE enum instance
 * @method static CAT_FACE() Returns the CAT_FACE enum instance
 * @method static SEE_NO_EVIL_MONKEY() Returns the SEE_NO_EVIL_MONKEY enum instance
 * @method static THINKING() Returns the THINKING enum instance
 * @method static TACO() Returns the TACO enum instance
 * @method static PIZZA() Returns the PIZZA enum instance
 * @method static BIRTHDAY_CAKE() Returns the BIRTHDAY_CAKE enum instance
 * @method static COFFEE() Returns the COFFEE enum instance
 * @method static BEER_MUG() Returns the BEER_MUG enum instance
 * @method static BOTTLE_WITH_POPPING_CORK() Returns the BOTTLE_WITH_POPPING_CORK enum instance
 * @method static HEART_EYES() Returns the HEART_EYES enum instance
 * @method static CLINKING_GLASSES() Returns the CLINKING_GLASSES enum instance
 * @method static RAINBOW() Returns the RAINBOW enum instance
 * @method static EARTH_GLOBE() Returns the EARTH_GLOBE enum instance
 * @method static SUN() Returns the SUN enum instance
 * @method static CRESCENT_MOON() Returns the CRESCENT_MOON enum instance
 * @method static SNOWFLAKE() Returns the SNOWFLAKE enum instance
 * @method static HIGH_VOLTAGE() Returns the HIGH_VOLTAGE enum instance
 * @method static CLOUD() Returns the CLOUD enum instance
 * @method static DROPLET() Returns the DROPLET enum instance
 * @method static MILKY_WAY() Returns the MILKY_WAY enum instance
 * @method static SPARKLING_HEART() Returns the SPARKLING_HEART enum instance
 * @method static CROWN() Returns the CROWN enum instance
 * @method static SPARKLES() Returns the SPARKLES enum instance
 * @method static FIREWORKS() Returns the FIREWORKS enum instance
 * @method static SMILING_FACE_WITH_HEARTS() Returns the SMILING_FACE_WITH_HEARTS enum instance
 * @method static FACE_WITH_MEDICAL_MASK() Returns the FACE_WITH_MEDICAL_MASK enum instance
 * @method static FACE_WITH_THERMOMETER() Returns the FACE_WITH_THERMOMETER enum instance
 * @method static FACE_WITH_HEAD_BANDAGE() Returns the FACE_WITH_HEAD_BANDAGE enum instance
 * @method static PLEADING_FACE() Returns the PLEADING_FACE enum instance
 * @method static YAWNING_FACE() Returns the YAWNING_FACE enum instance
 * @method static COWBOY_HAT_FACE() Returns the COWBOY_HAT_FACE enum instance
 * @method static FACE_WITH_MONOCLE() Returns the FACE_WITH_MONOCLE enum instance
 * @method static FACE_WITH_HAND_OVER_MOUTH() Returns the FACE_WITH_HAND_OVER_MOUTH enum instance
 * @method static FACE_WITH_ROLLING_EYES() Returns the FACE_WITH_ROLLING_EYES enum instance
 * @method static FOG() Returns the FOG enum instance
 * @method static MONKEY_FACE() Returns the MONKEY_FACE enum instance
 * @method static HEAR_NO_EVIL_MONKEY() Returns the HEAR_NO_EVIL_MONKEY enum instance
 * @method static SPEAK_NO_EVIL_MONKEY() Returns the SPEAK_NO_EVIL_MONKEY enum instance
 * @method static BEAR_FACE() Returns the BEAR_FACE enum instance
 * @method static KOALA() Returns the KOALA enum instance
 * @method static PANDA_FACE() Returns the PANDA_FACE enum instance
 * @method static UNICORN_FACE() Returns the UNICORN_FACE enum instance
 * @method static EAGLE() Returns the EAGLE enum instance
 * @method static FALCON() Returns the FALCON enum instance
 * @method static PEACOCK() Returns the PEACOCK enum instance
 * @method static SHARK() Returns the SHARK enum instance
 * @method static TURTLE() Returns the TURTLE enum instance
 * @method static OCTOPUS() Returns the OCTOPUS enum instance
 * @method static CRAB() Returns the CRAB enum instance
 * @method static SPIDER() Returns the SPIDER enum instance
 * @method static SPIDER_WEB() Returns the SPIDER_WEB enum instance
 * @method static LADY_BUG() Returns the LADY_BUG enum instance
 * @method static HONEYBEE() Returns the HONEYBEE enum instance
 * @method static SUNFLOWER() Returns the SUNFLOWER enum instance
 * @method static TULIP() Returns the TULIP enum instance
 * @method static DECIDUOUS_TREE() Returns the DECIDUOUS_TREE enum instance
 * @method static CACTUS() Returns the CACTUS enum instance
 * @method static HERB() Returns the HERB enum instance
 * @method static ROSE() Returns the ROSE enum instance
 * @method static BOUQUET() Returns the BOUQUET enum instance
 * @method static EARTH_GLOBE_EUROPE_AFRICA() Returns the EARTH_GLOBE_EUROPE_AFRICA enum instance
 * @method static EARTH_GLOBE_AMERICAS() Returns the EARTH_GLOBE_AMERICAS enum instance
 * @method static EARTH_GLOBE_ASIA_AUSTRALIA() Returns the EARTH_GLOBE_ASIA_AUSTRALIA enum instance
 * @method static GLOBE_WITH_MERIDIANS() Returns the GLOBE_WITH_MERIDIANS enum instance
 * @method static SPIRAL_CALENDAR() Returns the SPIRAL_CALENDAR enum instance
 * @method static NOTEBOOK() Returns the NOTEBOOK enum instance
 * @method static BOOKS() Returns the BOOKS enum instance
 * @method static OPEN_BOOK() Returns the OPEN_BOOK enum instance
 * @method static SCISSORS() Returns the SCISSORS enum instance
 * @method static PENCIL() Returns the PENCIL enum instance
 * @method static PEN() Returns the PEN enum instance
 * @method static PAINTBRUSH() Returns the PAINTBRUSH enum instance
 * @method static ARTIST_PALETTE() Returns the ARTIST_PALETTE enum instance
 * @method static MUSICAL_NOTE() Returns the MUSICAL_NOTE enum instance
 * @method static WAVING_HAND() Returns the WAVING_HAND enum instance
 * @method static RAISED_HAND() Returns the RAISED_HAND enum instance
 * @method static FLEXED_BICEPS() Returns the FLEXED_BICEPS enum instance
 * @method static RAISED_FIST() Returns the RAISED_FIST enum instance
 * @method static HANDSHAKE() Returns the HANDSHAKE enum instance
 * @method static RED_HEART() Returns the RED_HEART enum instance
 * @method static BROKEN_HEART() Returns the BROKEN_HEART enum instance
 * @method static TWO_HEARTS() Returns the TWO_HEARTS enum instance
 * @method static HEART_WITH_ARROW() Returns the HEART_WITH_ARROW enum instance
 * @method static HEART_WITH_RIBBON() Returns the HEART_WITH_RIBBON enum instance
 * @method static RING() Returns the RING enum instance
 * @method static WATER_WAVE() Returns the WATER_WAVE enum instance
 * @method static CHRISTMAS_TREE() Returns the CHRISTMAS_TREE enum instance
 * @method static WRAPPED_GIFT() Returns the WRAPPED_GIFT enum instance
 * @method static BALLOON() Returns the BALLOON enum instance
 * @method static CONFETTI_BALL() Returns the CONFETTI_BALL enum instance
 * @method static HORSE_FACE() Returns the HORSE_FACE enum instance
 * @method static PIG_FACE() Returns the PIG_FACE enum instance
 * @method static RABBIT_FACE() Returns the RABBIT_FACE enum instance
 * @method static LION_FACE() Returns the LION_FACE enum instance
 * @method static ELEPHANT() Returns the ELEPHANT enum instance
 * @method static CHICKEN() Returns the CHICKEN enum instance
 * @method static FROG() Returns the FROG enum instance
 * @method static PENGUIN() Returns the PENGUIN enum instance
 * @method static RED_APPLE() Returns the RED_APPLE enum instance
 * @method static BANANA() Returns the BANANA enum instance
 * @method static WATERMELON() Returns the WATERMELON enum instance
 * @method static SOFT_ICE_CREAM() Returns the SOFT_ICE_CREAM enum instance
 * @method static HAMBURGER() Returns the HAMBURGER enum instance
 * @method static HOT_DOG() Returns the HOT_DOG enum instance
 * @method static HOT_BEVERAGE() Returns the HOT_BEVERAGE enum instance
 * @method static TROPICAL_DRINK() Returns the TROPICAL_DRINK enum instance
 * @method static WINE_GLASS() Returns the WINE_GLASS enum instance
 * @method static MAN_RUNNING() Returns the MAN_RUNNING enum instance
 * @method static WOMAN_BIKING() Returns the WOMAN_BIKING enum instance
 * @method static MAN_SWIMMING() Returns the MAN_SWIMMING enum instance
 * @method static SKIER() Returns the SKIER enum instance
 * @method static SKATEBOARD() Returns the SKATEBOARD enum instance
 * @method static INFO() Returns the INFO enum instance
 * @method static SUCCESS() Returns the SUCCESS enum instance
 * @method static WARNING() Returns the WARNING enum instance
 * @method static BUG() Returns the BUG enum instance
 * @method static QUESTION() Returns the QUESTION enum instance
 * @method static EXCLAMATION() Returns the EXCLAMATION enum instance
 * @method static HOURGLASS_NOT_DONE() Returns the HOURGLASS_NOT_DONE enum instance
 * @method static HOURGLASS_DONE() Returns the HOURGLASS_DONE enum instance
 * @method static LIGHT_BULB() Returns the LIGHT_BULB enum instance
 * @method static LOCK() Returns the LOCK enum instance
 * @method static UNLOCK() Returns the UNLOCK enum instance
 * @method static ALARM_CLOCK() Returns the ALARM_CLOCK enum instance
 * @method static CLIPBOARD() Returns the CLIPBOARD enum instance
 * @method static PAGE_FACING_UP() Returns the PAGE_FACING_UP enum instance
 * @method static TOOLS() Returns the TOOLS enum instance
 * @method static GEAR() Returns the GEAR enum instance
 * @method static PUSH_PIN() Returns the PUSH_PIN enum instance
 * @method static CROSS_MARK() Returns the CROSS_MARK enum instance
 * @method static POLICE_CAR_LIGHT() Returns the POLICE_CAR_LIGHT enum instance
 */
enum Emoji: string
{
    use Enum;

    /**
     * Emoji for thumbs up.
     */
    #[Label('👍')]
    #[Description('Thumbs Up')]
    case THUMBS_UP = '👍';

    /**
     * Emoji for thumbs down.
     */
    #[Label('👎')]
    #[Description('Thumbs Down')]
    case THUMBS_DOWN = '👎';

    /**
     * Emoji for smile.
     */
    #[Label('😊')]
    #[Description('Smiling Face')]
    case SMILING_FACE = '😊';

    /**
     * Emoji for sad face.
     */
    #[Label('😢')]
    #[Description('Crying Face')]
    case CRYING_FACE = '😢';

    /**
     * Emoji for heart.
     */
    #[Label('❤️')]
    #[Description('Red Heart')]
    case RED_HEART = '❤️';

    /**
     * Emoji for star.
     */
    #[Label('⭐')]
    #[Description('Star')]
    case STAR = '⭐';

    /**
     * Emoji for fire.
     */
    #[Label('🔥')]
    #[Description('Fire')]
    case FIRE = '🔥';

    /**
     * Emoji for clapping.
     */
    #[Label('👏')]
    #[Description('Clapping Hands')]
    case CLAPPING_HANDS = '👏';

    /**
     * Emoji for party popper.
     */
    #[Label('🎉')]
    #[Description('Party Popper')]
    case PARTY_POPPER = '🎉';

    /**
     * Emoji for check mark.
     */
    #[Label('✅')]
    #[Description('Check Mark')]
    case SUCCESS = '✅';

    /**
     * Emoji for question mark.
     */
    #[Label('❓')]
    #[Description('Question Mark')]
    case QUESTION = '❓';

    /**
     * Emoji for exclamation mark.
     */
    #[Label('❗')]
    #[Description('Exclamation Mark')]
    case EXCLAMATION = '❗';

    /**
     * Emoji for laughing face.
     */
    #[Label('😂')]
    #[Description('Laughing Face')]
    case LAUGHING_FACE = '😂';

    /**
     * Emoji for winking face.
     */
    #[Label('😉')]
    #[Description('Winking Face')]
    case WINKING_FACE = '😉';

    /**
     * Emoji for angry face.
     */
    #[Label('😠')]
    #[Description('Angry Face')]
    case ANGRY_FACE = '😠';

    /**
     * Emoji for confused face.
     */
    #[Label('😕')]
    #[Description('Confused Face')]
    case CONFUSED_FACE = '😕';

    /**
     * Emoji for sunglasses face.
     */
    #[Label('😎')]
    #[Description('Smiling Face with Sunglasses')]
    case SMILING_FACE_WITH_SUNGGLASSES = '😎';

    /**
     * Emoji for thinking.
     */
    #[Label('🤔')]
    #[Description('Thinking Face')]
    case THINKING = '🤔';

    /**
     * Emoji for taco.
     */
    #[Label('🌮')]
    #[Description('Taco')]
    case TACO = '🌮';

    /**
     * Emoji for pizza.
     */
    #[Label('🍕')]
    #[Description('Pizza')]
    case PIZZA = '🍕';

    /**
     * Emoji for cake.
     */
    #[Label('🎂')]
    #[Description('Birthday Cake')]
    case BIRTHDAY_CAKE = '🎂';

    /**
     * Emoji for coffee.
     */
    #[Label('☕')]
    #[Description('Hot Beverage')]
    case HOT_BEVERAGE = '☕';

    /**
     * Emoji for beer.
     */
    #[Label('🍺')]
    #[Description('Beer Mug')]
    case BEER_MUG = '🍺';

    /**
     * Emoji for champagne.
     */
    #[Label('🍾')]
    #[Description('Bottle with Popping Cork')]
    case BOTTLE_WITH_POPPING_CORK = '🍾';

    /**
     * Emoji for heart eyes.
     */
    #[Label('😍')]
    #[Description('Heart Eyes')]
    case HEART_EYES = '😍';

    /**
     * Emoji for clinking glasses.
     */
    #[Label('🥂')]
    #[Description('Clinking Glasses')]
    case CLINKING_GLASSES = '🥂';

    /**
     * Emoji for rainbow.
     */
    #[Label('🌈')]
    #[Description('Rainbow')]
    case RAINBOW = '🌈';

    /**
     * Emoji for sun.
     */
    #[Label('☀️')]
    #[Description('Sun')]
    case SUN = '☀️';

    /**
     * Emoji for moon.
     */
    #[Label('🌙')]
    #[Description('Crescent Moon')]
    case CRESCENT_MOON = '🌙';

    /**
     * Emoji for snowflake.
     */
    #[Label('❄️')]
    #[Description('Snowflake')]
    case SNOWFLAKE = '❄️';

    /**
     * Emoji for lightning.
     */
    #[Label('⚡')]
    #[Description('High Voltage')]
    case HIGH_VOLTAGE = '⚡';

    /**
     * Emoji for cloud.
     */
    #[Label('☁️')]
    #[Description('Cloud')]
    case CLOUD = '☁️';

    /**
     * Emoji for raindrop.
     */
    #[Label('💧')]
    #[Description('Droplet')]
    case DROPLET = '💧';

    /**
     * Emoji for starry night.
     */
    #[Label('🌌')]
    #[Description('Milky Way')]
    case MILKY_WAY = '🌌';

    /**
     * Emoji for sparkling heart.
     */
    #[Label('💖')]
    #[Description('Sparkling Heart')]
    case SPARKLING_HEART = '💖';

    /**
     * Emoji for crown.
     */
    #[Label('👑')]
    #[Description('Crown')]
    case CROWN = '👑';

    /**
     * Emoji for sparkling stars.
     */
    #[Label('✨')]
    #[Description('Sparkles')]
    case SPARKLES = '✨';

    /**
     * Emoji for fireworks.
     */
    #[Label('🎆')]
    #[Description('Fireworks')]
    case FIREWORKS = '🎆';

    /**
     * Emoji for smiling face with hearts.
     */
    #[Label('🥰')]
    #[Description('Smiling Face with Hearts')]
    case SMILING_FACE_WITH_HEARTS = '🥰';

    /**
     * Emoji for face with medical mask.
     */
    #[Label('😷')]
    #[Description('Face with Medical Mask')]
    case FACE_WITH_MEDICAL_MASK = '😷';

    /**
     * Emoji for face with thermometer.
     */
    #[Label('🤒')]
    #[Description('Face with Thermometer')]
    case FACE_WITH_THERMOMETER = '🤒';

    /**
     * Emoji for face with head-bandage.
     */
    #[Label('🤕')]
    #[Description('Face with Head-Bandage')]
    case FACE_WITH_HEAD_BANDAGE = '🤕';

    /**
     * Emoji for pleading face.
     */
    #[Label('🥺')]
    #[Description('Pleading Face')]
    case PLEADING_FACE = '🥺';

    /**
     * Emoji for yawn.
     */
    #[Label('🥱')]
    #[Description('Yawning Face')]
    case YAWNING_FACE = '🥱';

    /**
     * Emoji for face with cowboy hat.
     */
    #[Label('🤠')]
    #[Description('Cowboy Hat Face')]
    case COWBOY_HAT_FACE = '🤠';

    /**
     * Emoji for face with monocle.
     */
    #[Label('🧐')]
    #[Description('Face with Monocle')]
    case FACE_WITH_MONOCLE = '🧐';

    /**
     * Emoji for face with hand over mouth.
     */
    #[Label('🤭')]
    #[Description('Face with Hand Over Mouth')]
    case FACE_WITH_HAND_OVER_MOUTH = '🤭';

    /**
     * Emoji for face with rolling eyes.
     */
    #[Label('🙄')]
    #[Description('Face with Rolling Eyes')]
    case FACE_WITH_ROLLING_EYES = '🙄';

    /**
     * Emoji for face in clouds.
     */
    #[Label('🌫️')]
    #[Description('Fog')]
    case FOG = '🌫️';

    /**
     * Emoji for monkey face.
     */
    #[Label('🐵')]
    #[Description('Monkey Face')]
    case MONKEY_FACE = '🐵';

    /**
     * Emoji for monkey covering ears.
     */
    #[Label('🙉')]
    #[Description('Hear-No-Evil Monkey')]
    case HEAR_NO_EVIL_MONKEY = '🙉';

    /**
     * Emoji for monkey covering mouth.
     */
    #[Label('🙊')]
    #[Description('Speak-No-Evil Monkey')]
    case SPEAK_NO_EVIL_MONKEY = '🙊';

    /**
     * Emoji for bear face.
     */
    #[Label('🐻')]
    #[Description('Bear Face')]
    case BEAR_FACE = '🐻';

    /**
     * Emoji for koala.
     */
    #[Label('🐨')]
    #[Description('Koala')]
    case KOALA = '🐨';

    /**
     * Emoji for panda face.
     */
    #[Label('🐼')]
    #[Description('Panda Face')]
    case PANDA_FACE = '🐼';

    /**
     * Emoji for unicorn.
     */
    #[Label('🦄')]
    #[Description('Unicorn Face')]
    case UNICORN_FACE = '🦄';

    /**
     * Emoji for falcon.
     */
    #[Label('🦅')]
    #[Description('Falcon')]
    case FALCON = '🦅';

    /**
     * Emoji for peacock.
     */
    #[Label('🦚')]
    #[Description('Peacock')]
    case PEACOCK = '🦚';

    /**
     * Emoji for shark.
     */
    #[Label('🦈')]
    #[Description('Shark')]
    case SHARK = '🦈';

    /**
     * Emoji for turtle.
     */
    #[Label('🐢')]
    #[Description('Turtle')]
    case TURTLE = '🐢';

    /**
     * Emoji for octopus.
     */
    #[Label('🐙')]
    #[Description('Octopus')]
    case OCTOPUS = '🐙';

    /**
     * Emoji for crab.
     */
    #[Label('🦀')]
    #[Description('Crab')]
    case CRAB = '🦀';

    /**
     * Emoji for spider.
     */
    #[Label('🕷️')]
    #[Description('Spider')]
    case SPIDER = '🕷️';

    /**
     * Emoji for spider web.
     */
    #[Label('🕸️')]
    #[Description('Spider Web')]
    case SPIDER_WEB = '🕸️';

    /**
     * Emoji for lady beetle.
     */
    #[Label('🐞')]
    #[Description('Lady Beetle')]
    case LADY_BUG = '🐞';

    /**
     * Emoji for honeybee.
     */
    #[Label('🐝')]
    #[Description('Honeybee')]
    case HONEYBEE = '🐝';

    /**
     * Emoji for sunflower.
     */
    #[Label('🌻')]
    #[Description('Sunflower')]
    case SUNFLOWER = '🌻';

    /**
     * Emoji for sunflower.
     */
    #[Label('🌷')]
    #[Description('Tulip')]
    case TULIP = '🌷';

    /**
     * Emoji for tree.
     */
    #[Label('🌳')]
    #[Description('Deciduous Tree')]
    case DECIDUOUS_TREE = '🌳';

    /**
     * Emoji for cactus.
     */
    #[Label('🌵')]
    #[Description('Cactus')]
    case CACTUS = '🌵';

    /**
     * Emoji for herb.
     */
    #[Label('🌿')]
    #[Description('Herb')]
    case HERB = '🌿';

    /**
     * Emoji for rose.
     */
    #[Label('🌹')]
    #[Description('Rose')]
    case ROSE = '🌹';

    /**
     * Emoji for bouquet.
     */
    #[Label('💐')]
    #[Description('Bouquet')]
    case BOUQUET = '💐';

    /**
     * Emoji for globe showing Europe and Africa.
     */
    #[Label('🌍')]
    #[Description('Earth Globe Europe-Africa')]
    case EARTH_GLOBE_EUROPE_AFRICA = '🌍';

    /**
     * Emoji for globe showing Americas.
     */
    #[Label('🌎')]
    #[Description('Earth Globe Americas')]
    case EARTH_GLOBE_AMERICAS = '🌎';

    /**
     * Emoji for globe showing Asia and Australia.
     */
    #[Label('🌏')]
    #[Description('Earth Globe Asia-Australia')]
    case EARTH_GLOBE_ASIA_AUSTRALIA = '🌏';

    /**
     * Emoji for globe with meridians.
     */
    #[Label('🌐')]
    #[Description('Globe with Meridians')]
    case GLOBE_WITH_MERIDIANS = '🌐';

    /**
     * Emoji for spiral calendar.
     */
    #[Label('🗓️')]
    #[Description('Spiral Calendar')]
    case SPIRAL_CALENDAR = '🗓️';

    /**
     * Emoji for notebook.
     */
    #[Label('📒')]
    #[Description('Notebook')]
    case NOTEBOOK = '📒';

    /**
     * Emoji for book.
     */
    #[Label('📚')]
    #[Description('Books')]
    case BOOKS = '📚';

    /**
     * Emoji for opened book.
     */
    #[Label('📖')]
    #[Description('Open Book')]
    case OPEN_BOOK = '📖';

    /**
     * Emoji for scissors.
     */
    #[Label('✂️')]
    #[Description('Scissors')]
    case SCISSORS = '✂️';

    /**
     * Emoji for pencil.
     */
    #[Label('✏️')]
    #[Description('Pencil')]
    case PENCIL = '✏️';

    /**
     * Emoji for pen.
     */
    #[Label('🖊️')]
    #[Description('Pen')]
    case PEN = '🖊️';

    /**
     * Emoji for paintbrush.
     */
    #[Label('🖌️')]
    #[Description('Paintbrush')]
    case PAINTBRUSH = '🖌️';

    /**
     * Emoji for palette.
     */
    #[Label('🎨')]
    #[Description('Artist Palette')]
    case ARTIST_PALETTE = '🎨';

    /**
     * Emoji for musical note.
     */
    #[Label('🎵')]
    #[Description('Musical Note')]
    case MUSICAL_NOTE = '🎵';

    /**
     * Emoji for waving hand.
     */
    #[Label('👋')]
    #[Description('Waving Hand')]
    case WAVING_HAND = '👋';

    /**
     * Emoji for raised hand.
     */
    #[Label('✋')]
    #[Description('Raised Hand')]
    case RAISED_HAND = '✋';

    /**
     * Emoji for flexed biceps.
     */
    #[Label('💪')]
    #[Description('Flexed Biceps')]
    case FLEXED_BICEPS = '💪';

    /**
     * Emoji for raised fist.
     */
    #[Label('✊')]
    #[Description('Raised Fist')]
    case RAISED_FIST = '✊';

    /**
     * Emoji for handshake.
     */
    #[Label('🤝')]
    #[Description('Handshake')]
    case HANDSHAKE = '🤝';

    /**
     * Emoji for broken heart.
     */
    #[Label('💔')]
    #[Description('Broken Heart')]
    case BROKEN_HEART = '💔';

    /**
     * Emoji for two hearts.
     */
    #[Label('💕')]
    #[Description('Two Hearts')]
    case TWO_HEARTS = '💕';

    /**
     * Emoji for heart with arrow.
     */
    #[Label('💘')]
    #[Description('Heart with Arrow')]
    case HEART_WITH_ARROW = '💘';

    /**
     * Emoji for heart with ribbon.
     */
    #[Label('💝')]
    #[Description('Heart with Ribbon')]
    case HEART_WITH_RIBBON = '💝';

    /**
     * Emoji for wedding.
     */
    #[Label('💍')]
    #[Description('Ring')]
    case RING = '💍';

    /**
     * Emoji for water wave.
     */
    #[Label('🌊')]
    #[Description('Water Wave')]
    case WATER_WAVE = '🌊';

    /**
     * Emoji for Christmas tree.
     */
    #[Label('🎄')]
    #[Description('Christmas Tree')]
    case CHRISTMAS_TREE = '🎄';

    /**
     * Emoji for gift.
     */
    #[Label('🎁')]
    #[Description('Wrapped Gift')]
    case WRAPPED_GIFT = '🎁';

    /**
     * Emoji for balloon.
     */
    #[Label('🎈')]
    #[Description('Balloon')]
    case BALLOON = '🎈';

    /**
     * Emoji for confetti ball.
     */
    #[Label('🎊')]
    #[Description('Confetti Ball')]
    case CONFETTI_BALL = '🎊';

    /**
     * Emoji for horse.
     */
    #[Label('🐴')]
    #[Description('Horse Face')]
    case HORSE_FACE = '🐴';

    /**
     * Emoji for pig.
     */
    #[Label('🐷')]
    #[Description('Pig Face')]
    case PIG_FACE = '🐷';

    /**
     * Emoji for rabbit.
     */
    #[Label('🐰')]
    #[Description('Rabbit Face')]
    case RABBIT_FACE = '🐰';

    /**
     * Emoji for lion.
     */
    #[Label('🦁')]
    #[Description('Lion Face')]
    case LION_FACE = '🦁';

    /**
     * Emoji for elephant.
     */
    #[Label('🐘')]
    #[Description('Elephant')]
    case ELEPHANT = '🐘';

    /**
     * Emoji for chicken.
     */
    #[Label('🐔')]
    #[Description('Chicken')]
    case CHICKEN = '🐔';

    /**
     * Emoji for frog.
     */
    #[Label('🐸')]
    #[Description('Frog')]
    case FROG = '🐸';

    /**
     * Emoji for penguin.
     */
    #[Label('🐧')]
    #[Description('Penguin')]
    case PENGUIN = '🐧';

    /**
     * Emoji for apple.
     */
    #[Label('🍎')]
    #[Description('Red Apple')]
    case RED_APPLE = '🍎';

    /**
     * Emoji for banana.
     */
    #[Label('🍌')]
    #[Description('Banana')]
    case BANANA = '🍌';

    /**
     * Emoji for watermelon.
     */
    #[Label('🍉')]
    #[Description('Watermelon')]
    case WATERMELON = '🍉';

    /**
     * Emoji for ice cream.
     */
    #[Label('🍦')]
    #[Description('Soft Ice Cream')]
    case SOFT_ICE_CREAM = '🍦';

    /**
     * Emoji for burger.
     */
    #[Label('🍔')]
    #[Description('Hamburger')]
    case HAMBURGER = '🍔';

    /**
     * Emoji for hot dog.
     */
    #[Label('🌭')]
    #[Description('Hot Dog')]
    case HOT_DOG = '🌭';

    /**
     * Emoji for cocktail.
     */
    #[Label('🍹')]
    #[Description('Tropical Drink')]
    case TROPICAL_DRINK = '🍹';

    /**
     * Emoji for wine glass.
     */
    #[Label('🍷')]
    #[Description('Wine Glass')]
    case WINE_GLASS = '🍷';

    /**
     * Emoji for running.
     */
    #[Label('🏃‍♂️')]
    #[Description('Man Running')]
    case MAN_RUNNING = '🏃‍♂️';

    /**
     * Emoji for cycling.
     */
    #[Label('🚴‍♀️')]
    #[Description('Woman Biking')]
    case WOMAN_BIKING = '🚴‍♀️';

    /**
     * Emoji for swimming.
     */
    #[Label('🏊‍♂️')]
    #[Description('Man Swimming')]
    case MAN_SWIMMING = '🏊‍♂️';

    /**
     * Emoji for skiing.
     */
    #[Label('⛷️')]
    #[Description('Skier')]
    case SKIER = '⛷️';

    /**
     * Emoji for skateboard.
     */
    #[Label('🛹')]
    #[Description('Skateboard')]
    case SKATEBOARD = '🛹';

    /**
     * Emoji for information.
     */
    #[Label('ℹ️')]
    #[Description('Information')]
    case INFO = 'ℹ️';

    /**
     * Emoji for warning.
     */
    #[Label('⚠️')]
    #[Description('Warning')]
    case WARNING = '⚠️';

    /**
     * Emoji for bug.
     */
    #[Label('🐛')]
    #[Description('Bug')]
    case BUG = '🐛';

    /**
     * Emoji for hourglass.
     */
    #[Label('⏳')]
    #[Description('Hourglass Not Done')]
    case HOURGLASS_NOT_DONE = '⏳';

    /**
     * Emoji for hourglass done.
     */
    #[Label('⌛')]
    #[Description('Hourglass Done')]
    case HOURGLASS_DONE = '⌛';

    /**
     * Emoji for light bulb.
     */
    #[Label('💡')]
    #[Description('Light Bulb')]
    case LIGHT_BULB = '💡';

    /**
     * Emoji for lock.
     */
    #[Label('🔒')]
    #[Description('Locked')]
    case LOCK = '🔒';

    /**
     * Emoji for unlock.
     */
    #[Label('🔓')]
    #[Description('Unlocked')]
    case UNLOCK = '🔓';

    /**
     * Emoji for alarm clock.
     */
    #[Label('⏰')]
    #[Description('Alarm Clock')]
    case ALARM_CLOCK = '⏰';

    /**
     * Emoji for clipboard.
     */
    #[Label('📋')]
    #[Description('Clipboard')]
    case CLIPBOARD = '📋';

    /**
     * Emoji for paper.
     */
    #[Label('📄')]
    #[Description('Page Facing Up')]
    case PAGE_FACING_UP = '📄';

    /**
     * Emoji for tools.
     */
    #[Label('🛠️')]
    #[Description('Hammer and Wrench')]
    case TOOLS = '🛠️';

    /**
     * Emoji for gears.
     */
    #[Label('⚙️')]
    #[Description('Gear')]
    case GEAR = '⚙️';

    /**
     * Emoji for pushpin.
     */
    #[Label('📌')]
    #[Description('Pushpin')]
    case PUSH_PIN = '📌';

    /**
     * Emoji for cross mark.
     */
    #[Label('❌')]
    #[Description('Cross Mark')]
    case CROSS_MARK = '❌';

    /**
     * Emoji for police car light (alert).
     */
    #[Label('🚨')]
    #[Description('Police Car Light')]
    case POLICE_CAR_LIGHT = '🚨';
}
