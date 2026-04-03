<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum representing common content types.
 *
 * @method static TEXT_PLAIN() Returns the TEXT_PLAIN enum instance
 * @method static APPLICATION_JSON() Returns the APPLICATION_JSON enum instance
 * @method static APPLICATION_XML() Returns the APPLICATION_XML enum instance
 * @method static APPLICATION_FORM_URL_ENCODED() Returns the APPLICATION_FORM_URL_ENCODED enum instance
 * @method static APPLICATION_X_PHP() Returns the APPLICATION_X_PHP enum instance
 * @method static APPLICATION_VND_HZN_3D_CROSSWORD() Returns the APPLICATION_VND_HZN_3D_CROSSWORD enum instance
 * @method static APPLICATION_VND_MSEQ() Returns the APPLICATION_VND_MSEQ enum instance
 * @method static APPLICATION_VND_3M_POST_IT_NOTES() Returns the APPLICATION_VND_3M_POST_IT_NOTES enum instance
 * @method static APPLICATION_VND_3GPP_PIC_BW_LARGE() Returns the APPLICATION_VND_3GPP_PIC_BW_LARGE enum instance
 * @method static APPLICATION_VND_3GPP_PIC_BW_SMALL() Returns the APPLICATION_VND_3GPP_PIC_BW_SMALL enum instance
 * @method static APPLICATION_VND_3GPP_PIC_BW_VAR() Returns the APPLICATION_VND_3GPP_PIC_BW_VAR enum instance
 * @method static APPLICATION_VND_3GPP2_TCAP() Returns the APPLICATION_VND_3GPP2_TCAP enum instance
 * @method static APPLICATION_X_7Z_COMPRESSED() Returns the APPLICATION_X_7Z_COMPRESSED enum instance
 * @method static APPLICATION_X_ABIWORD() Returns the APPLICATION_X_ABIWORD enum instance
 * @method static APPLICATION_X_ACE_COMPRESSED() Returns the APPLICATION_X_ACE_COMPRESSED enum instance
 * @method static APPLICATION_VND_AMERICANDYNAMICS_ACC() Returns the APPLICATION_VND_AMERICANDYNAMICS_ACC enum instance
 * @method static APPLICATION_VND_ACUCORP() Returns the APPLICATION_VND_ACUCORP enum instance
 * @method static APPLICATION_X_AUTHORWARE_BIN() Returns the APPLICATION_X_AUTHORWARE_BIN enum instance
 * @method static APPLICATION_X_AUTHORWARE_MAP() Returns the APPLICATION_X_AUTHORWARE_MAP enum instance
 * @method static APPLICATION_X_AUTHORWARE_SEG() Returns the APPLICATION_X_AUTHORWARE_SEG enum instance
 * @method static APPLICATION_VND_ADOBE_AIR_APPLICATION_INSTALLER_PACKAGE_ZIP() Returns the APPLICATION_VND_ADOBE_AIR_APPLICATION_INSTALLER_PACKAGE_ZIP enum instance
 * @method static APPLICATION_X_SHOCKWAVE_FLASH() Returns the APPLICATION_X_SHOCKWAVE_FLASH enum instance
 * @method static APPLICATION_VND_ADOBE_FXP() Returns the APPLICATION_VND_ADOBE_FXP enum instance
 * @method static APPLICATION_PDF() Returns the APPLICATION_PDF enum instance
 * @method static APPLICATION_VND_CUPS_PPD() Returns the APPLICATION_VND_CUPS_PPD enum instance
 * @method static APPLICATION_X_DIRECTOR() Returns the APPLICATION_X_DIRECTOR enum instance
 * @method static APPLICATION_VND_ADOBE_XDP_XML() Returns the APPLICATION_VND_ADOBE_XDP_XML enum instance
 * @method static APPLICATION_VND_ADOBE_XFDF() Returns the APPLICATION_VND_ADOBE_XFDF enum instance
 * @method static APPLICATION_VND_AHEAD_SPACE() Returns the APPLICATION_VND_AHEAD_SPACE enum instance
 * @method static APPLICATION_VND_AIRZIP_FILESECURE_AZF() Returns the APPLICATION_VND_AIRZIP_FILESECURE_AZF enum instance
 * @method static APPLICATION_VND_AIRZIP_FILESECURE_AZS() Returns the APPLICATION_VND_AIRZIP_FILESECURE_AZS enum instance
 * @method static APPLICATION_VND_AMAZON_EBOOK() Returns the APPLICATION_VND_AMAZON_EBOOK enum instance
 */
enum ContentType: string
{
    use Enum;

    /**
     * Text plain content type.
     */
    #[Label('Text Plain')]
    #[Description('Represents text/plain content type.')]
    case TEXT_PLAIN = 'text/plain';

    /**
     * JSON content type.
     */
    #[Label('JSON')]
    #[Description('Represents application/json content type.')]
    case APPLICATION_JSON = 'application/json';

    /**
     * XML content type.
     */
    #[Label('XML')]
    #[Description('Represents application/xml content type.')]
    case APPLICATION_XML = 'application/xml';

    /**
     * Form URL encoded content type.
     */
    #[Label('Form URL Encoded')]
    #[Description('Represents application/x-www-form-urlencoded content type.')]
    case APPLICATION_FORM_URL_ENCODED = 'application/x-www-form-urlencoded';

    /**
     * PHP script file.
     */
    #[Label('PHP')]
    #[Description('Represents application/x-php content type.')]
    case APPLICATION_X_PHP = 'application/x-php';

    /**
     * 3D Crossword file.
     */
    #[Label('3D Crossword')]
    #[Description('Represents application/vnd.hzn-3d-crossword content type.')]
    case APPLICATION_VND_HZN_3D_CROSSWORD = 'application/vnd.hzn-3d-crossword';

    /**
     * MSEQ application file.
     */
    #[Label('MSEQ')]
    #[Description('Represents application/vnd.mseq content type.')]
    case APPLICATION_VND_MSEQ = 'application/vnd.mseq';

    /**
     * 3M Post-It Notes file.
     */
    #[Label('3M Post-It Notes')]
    #[Description('Represents application/vnd.3m.post-it-notes content type.')]
    case APPLICATION_VND_3M_POST_IT_NOTES = 'application/vnd.3m.post-it-notes';

    /**
     * 3GPP Large Picture file.
     */
    #[Label('3GPP Large Picture')]
    #[Description('Represents application/vnd.3gpp.pic-bw-large content type.')]
    case APPLICATION_VND_3GPP_PIC_BW_LARGE = 'application/vnd.3gpp.pic-bw-large';

    /**
     * 3GPP Small Picture file.
     */
    #[Label('3GPP Small Picture')]
    #[Description('Represents application/vnd.3gpp.pic-bw-small content type.')]
    case APPLICATION_VND_3GPP_PIC_BW_SMALL = 'application/vnd.3gpp.pic-bw-small';

    /**
     * 3GPP Variable Picture file.
     */
    #[Label('3GPP Variable Picture')]
    #[Description('Represents application/vnd.3gpp.pic-bw-var content type.')]
    case APPLICATION_VND_3GPP_PIC_BW_VAR = 'application/vnd.3gpp.pic-bw-var';

    /**
     * 3GPP2 TCAP file.
     */
    #[Label('3GPP2 TCAP')]
    #[Description('Represents application/vnd.3gpp2.tcap content type.')]
    case APPLICATION_VND_3GPP2_TCAP = 'application/vnd.3gpp2.tcap';

    /**
     * 7-Zip compressed file.
     */
    #[Label('7-Zip Compressed')]
    #[Description('Represents application/x-7z-compressed content type.')]
    case APPLICATION_X_7Z_COMPRESSED = 'application/x-7z-compressed';

    /**
     * AbiWord document.
     */
    #[Label('AbiWord')]
    #[Description('Represents application/x-abiword content type.')]
    case APPLICATION_X_ABIWORD = 'application/x-abiword';

    /**
     * ACE archive.
     */
    #[Label('ACE Archive')]
    #[Description('Represents application/x-ace-compressed content type.')]
    case APPLICATION_X_ACE_COMPRESSED = 'application/x-ace-compressed';

    /**
     * ACCDB Microsoft Access database file.
     */
    #[Label('Microsoft Access')]
    #[Description('Represents application/vnd.americandynamics.acc content type.')]
    case APPLICATION_VND_AMERICANDYNAMICS_ACC = 'application/vnd.americandynamics.acc';

    /**
     * ACU Corp data.
     */
    #[Label('ACU Corp')]
    #[Description('Represents application/vnd.acucorp content type.')]
    case APPLICATION_VND_ACUCORP = 'application/vnd.acucorp';

    /**
     * Authorware binary file.
     */
    #[Label('Authorware Binary')]
    #[Description('Represents application/x-authorware-bin content type.')]
    case APPLICATION_X_AUTHORWARE_BIN = 'application/x-authorware-bin';

    /**
     * Authorware map file.
     */
    #[Label('Authorware Map')]
    #[Description('Represents application/x-authorware-map content type.')]
    case APPLICATION_X_AUTHORWARE_MAP = 'application/x-authorware-map';

    /**
     * Authorware segment file.
     */
    #[Label('Authorware Segment')]
    #[Description('Represents application/x-authorware-seg content type.')]
    case APPLICATION_X_AUTHORWARE_SEG = 'application/x-authorware-seg';

    /**
     * Adobe AIR installer package.
     */
    #[Label('Adobe AIR Installer')]
    #[Description('Represents application/vnd.adobe.air-application-installer-package+zip content type.')]
    case APPLICATION_VND_ADOBE_AIR_APPLICATION_INSTALLER_PACKAGE_ZIP = 'application/vnd.adobe.air-application-installer-package+zip';

    /**
     * Adobe Shockwave Flash file.
     */
    #[Label('Adobe Flash')]
    #[Description('Represents application/x-shockwave-flash content type.')]
    case APPLICATION_X_SHOCKWAVE_FLASH = 'application/x-shockwave-flash';

    /**
     * Adobe Flex file.
     */
    #[Label('Adobe Flex')]
    #[Description('Represents application/vnd.adobe.fxp content type.')]
    case APPLICATION_VND_ADOBE_FXP = 'application/vnd.adobe.fxp';

    /**
     * Portable Document Format (PDF).
     */
    #[Label('PDF')]
    #[Description('Represents application/pdf content type.')]
    case APPLICATION_PDF = 'application/pdf';

    /**
     * CUPS (Common UNIX Printing System) PPD (PostScript Printer Description) file.
     */
    #[Label('CUPS PPD')]
    #[Description('Represents application/vnd.cups-ppd content type.')]
    case APPLICATION_VND_CUPS_PPD = 'application/vnd.cups-ppd';

    /**
     * Adobe Director file.
     */
    #[Label('Adobe Director')]
    #[Description('Represents application/x-director content type.')]
    case APPLICATION_X_DIRECTOR = 'application/x-director';

    /**
     * Adobe XML Data Package file.
     */
    #[Label('Adobe XML Data Package')]
    #[Description('Represents application/vnd.adobe.xdp+xml content type.')]
    case APPLICATION_VND_ADOBE_XDP_XML = 'application/vnd.adobe.xdp+xml';

    /**
     * Adobe XML Forms Data Format (XFDF) file.
     */
    #[Label('Adobe XFDF')]
    #[Description('Represents application/vnd.adobe.xfdf content type.')]
    case APPLICATION_VND_ADOBE_XFDF = 'application/vnd.adobe.xfdf';

    /**
     * Ahead AIR application.
     */
    #[Label('Ahead AIR')]
    #[Description('Represents application/vnd.ahead.space content type.')]
    case APPLICATION_VND_AHEAD_SPACE = 'application/vnd.ahead.space';

    /**
     * AirZip FileSECURE AZF file.
     */
    #[Label('AirZip FileSECURE AZF')]
    #[Description('Represents application/vnd.airzip.filesecure.azf content type.')]
    case APPLICATION_VND_AIRZIP_FILESECURE_AZF = 'application/vnd.airzip.filesecure.azf';

    /**
     * AirZip FileSECURE AZS file.
     */
    #[Label('AirZip FileSECURE AZS')]
    #[Description('Represents application/vnd.airzip.filesecure.azs content type.')]
    case APPLICATION_VND_AIRZIP_FILESECURE_AZS = 'application/vnd.airzip.filesecure.azs';

    /**
     * Amazon Kindle eBook file.
     */
    #[Label('Amazon Kindle eBook')]
    #[Description('Represents application/vnd.amazon.ebook content type.')]
    case APPLICATION_VND_AMAZON_EBOOK = 'application/vnd.amazon.ebook';
}
