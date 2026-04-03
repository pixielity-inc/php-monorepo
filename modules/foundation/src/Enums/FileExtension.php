<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Defines various file extensions as enumerations.
 *
 * @method static TXT() Returns the TXT enum instance
 * @method static SVG() Returns the SVG enum instance
 * @method static DOC() Returns the DOC enum instance
 * @method static DOCX() Returns the DOCX enum instance
 * @method static PDF() Returns the PDF enum instance
 * @method static JPG() Returns the JPG enum instance
 * @method static JPEG() Returns the JPEG enum instance
 * @method static PNG() Returns the PNG enum instance
 * @method static GIF() Returns the GIF enum instance
 * @method static MP4() Returns the MP4 enum instance
 * @method static MOV() Returns the MOV enum instance
 * @method static AVI() Returns the AVI enum instance
 * @method static MP3() Returns the MP3 enum instance
 * @method static WAV() Returns the WAV enum instance
 * @method static ZIP() Returns the ZIP enum instance
 * @method static TAR() Returns the TAR enum instance
 * @method static GZ() Returns the GZ enum instance
 * @method static PHP() Returns the PHP enum instance
 * @method static TTF() Returns the TTF enum instance
 * @method static WOFF() Returns the WOFF enum instance
 * @method static WOFF2() Returns the WOFF2 enum instance
 * @method static LOG() Returns the LOG enum instance
 * @method static XML() Returns the XML enum instance
 * @method static JSON() Returns the JSON enum instance
 * @method static CSV() Returns the CSV enum instance
 * @method static WEBP() Returns the WEBP enum instance
 * @method static FLF() Returns the FLF enum instance
 */
enum FileExtension: string
{
    use Enum;

    /**
     * Text file extension.
     */
    #[Label('Text File')]
    #[Description('Represents a plain text file with the .txt extension.')]
    case TXT = 'txt';

    /**
     * SVG file extension.
     */
    #[Label('SVG File')]
    #[Description('Represents a Scalable Vector Graphics file with the .svg extension.')]
    case SVG = 'svg';

    /**
     * Word document file extension.
     */
    #[Label('Word Document (Old)')]
    #[Description('Represents a Microsoft Word document with the .doc extension.')]
    case DOC = 'doc';

    /**
     * Word document file extension (newer version).
     */
    #[Label('Word Document (New)')]
    #[Description('Represents an XML-based Microsoft Word document with the .docx extension.')]
    case DOCX = 'docx';

    /**
     * PDF file extension.
     */
    #[Label('PDF File')]
    #[Description('Represents a Portable Document Format file with the .pdf extension.')]
    case PDF = 'pdf';

    /**
     * JPEG image file extension.
     */
    #[Label('JPEG Image')]
    #[Description('Represents a JPEG image file with the .jpg extension.')]
    case JPG = 'jpg';

    /**
     * JPEG image file extension (alternative).
     */
    #[Label('JPEG Image (Alternative)')]
    #[Description('Represents a JPEG image file with the .jpeg extension.')]
    case JPEG = 'jpeg';

    /**
     * PNG image file extension.
     */
    #[Label('PNG Image')]
    #[Description('Represents a Portable Network Graphics image file with the .png extension.')]
    case PNG = 'png';

    /**
     * GIF image file extension.
     */
    #[Label('GIF Image')]
    #[Description('Represents a Graphics Interchange Format image file with the .gif extension.')]
    case GIF = 'gif';

    /**
     * MP4 video file extension.
     */
    #[Label('MP4 Video')]
    #[Description('Represents an MPEG-4 video file with the .mp4 extension.')]
    case MP4 = 'mp4';

    /**
     * MOV video file extension.
     */
    #[Label('MOV Video')]
    #[Description('Represents an Apple QuickTime movie file with the .mov extension.')]
    case MOV = 'mov';

    /**
     * AVI video file extension.
     */
    #[Label('AVI Video')]
    #[Description('Represents an Audio Video Interleave file with the .avi extension.')]
    case AVI = 'avi';

    /**
     * MP3 audio file extension.
     */
    #[Label('MP3 Audio')]
    #[Description('Represents an MPEG Layer 3 audio file with the .mp3 extension.')]
    case MP3 = 'mp3';

    /**
     * WAV audio file extension.
     */
    #[Label('WAV Audio')]
    #[Description('Represents a Waveform Audio File Format with the .wav extension.')]
    case WAV = 'wav';

    /**
     * ZIP compressed file extension.
     */
    #[Label('ZIP Archive')]
    #[Description('Represents a compressed archive file with the .zip extension.')]
    case ZIP = 'zip';

    /**
     * TAR compressed file extension.
     */
    #[Label('TAR Archive')]
    #[Description('Represents a Unix tape archive file with the .tar extension.')]
    case TAR = 'tar';

    /**
     * GZ compressed file extension.
     */
    #[Label('GZ Archive')]
    #[Description('Represents a Gzip compressed archive file with the .gz extension.')]
    case GZ = 'gz';

    /**
     * PHP file extension.
     */
    #[Label('PHP File')]
    #[Description('Represents a PHP script file with the .php extension.')]
    case PHP = 'php';

    /**
     * TrueType font file extension.
     */
    #[Label('TrueType Font')]
    #[Description('Represents a TrueType font file with the .ttf extension.')]
    case TTF = 'ttf';

    /**
     * Web Open Font Format file extension.
     */
    #[Label('WOFF Font')]
    #[Description('Represents a Web Open Font Format file with the .woff extension.')]
    case WOFF = 'woff';

    /**
     * Web Open Font Format file extension (version 2).
     */
    #[Label('WOFF2 Font')]
    #[Description('Represents a Web Open Font Format file (version 2) with the .woff2 extension.')]
    case WOFF2 = 'woff2';

    /**
     * Log file extension.
     */
    #[Label('Log File')]
    #[Description('Represents a log file with the .log extension.')]
    case LOG = 'log';

    /**
     * XML file extension.
     */
    #[Label('XML File')]
    #[Description('Represents an XML file with the .xml extension.')]
    case XML = 'xml';

    /**
     * JSON file extension.
     */
    #[Label('JSON File')]
    #[Description('Represents a JSON file with the .json extension.')]
    case JSON = 'json';

    /**
     * CSV file extension.
     */
    #[Label('CSV File')]
    #[Description('Represents a CSV file with the .csv extension.')]
    case CSV = 'csv';

    /**
     * WebP image file extension.
     */
    #[Label('WebP Image')]
    #[Description('Represents a WebP image file with the .webp extension.')]
    case WEBP = 'webp';

    /**
     * Figlet Font file extension.
     */
    #[Label('Figlet Font File')]
    #[Description('Represents a Figlet font file with the .flf extension, commonly used for ASCII art.')]
    case FLF = 'flf';
}
