<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum representing locales.
 *
 * @enum {string}
 *
 * @method static AF_NA() Returns the AF_NA enum instance
 * @method static AF_ZA() Returns the AF_ZA enum instance
 * @method static AK() Returns the AK enum instance
 * @method static AK_GH() Returns the AK_GH enum instance
 * @method static SQ_AL() Returns the SQ_AL enum instance
 * @method static SQ_XK() Returns the SQ_XK enum instance
 * @method static SQ_MK() Returns the SQ_MK enum instance
 * @method static AM_ET() Returns the AM_ET enum instance
 * @method static AR_DZ() Returns the AR_DZ enum instance
 * @method static AR_BH() Returns the AR_BH enum instance
 * @method static AR_TD() Returns the AR_TD enum instance
 * @method static AR_KM() Returns the AR_KM enum instance
 * @method static AR_DJ() Returns the AR_DJ enum instance
 * @method static AR_EG() Returns the AR_EG enum instance
 * @method static AR_ER() Returns the AR_ER enum instance
 * @method static AR_IQ() Returns the AR_IQ enum instance
 * @method static AR_IL() Returns the AR_IL enum instance
 * @method static AR_JO() Returns the AR_JO enum instance
 * @method static AR_KW() Returns the AR_KW enum instance
 * @method static AR_LB() Returns the AR_LB enum instance
 * @method static AR_LY() Returns the AR_LY enum instance
 * @method static AR_MR() Returns the AR_MR enum instance
 * @method static AR_MA() Returns the AR_MA enum instance
 * @method static AR_OM() Returns the AR_OM enum instance
 * @method static AR_PS() Returns the AR_PS enum instance
 * @method static AR_QA() Returns the AR_QA enum instance
 * @method static AR_SA() Returns the AR_SA enum instance
 * @method static AR_SO() Returns the AR_SO enum instance
 * @method static AR_SS() Returns the AR_SS enum instance
 * @method static AR_SD() Returns the AR_SD enum instance
 * @method static AR_SY() Returns the AR_SY enum instance
 * @method static AR_TN() Returns the AR_TN enum instance
 * @method static AR_AE() Returns the AR_AE enum instance
 * @method static AR_EH() Returns the AR_EH enum instance
 * @method static AR_YE() Returns the AR_YE enum instance
 * @method static HY_AM() Returns the HY_AM enum instance
 * @method static AS_IN() Returns the AS_IN enum instance
 * @method static AZ_AZ() Returns the AZ_AZ enum instance
 * @method static AZ_CYRL_AZ() Returns the AZ_CYRL_AZ enum instance
 * @method static AZ_CYRL() Returns the AZ_CYRL enum instance
 * @method static AZ_LATN_AZ() Returns the AZ_LATN_AZ enum instance
 * @method static AZ_LATN() Returns the AZ_LATN enum instance
 * @method static BM() Returns the BM enum instance
 * @method static BM_LATN_ML() Returns the BM_LATN_ML enum instance
 * @method static BM_LATN() Returns the BM_LATN enum instance
 * @method static EU_ES() Returns the EU_ES enum instance
 * @method static BE_BY() Returns the BE_BY enum instance
 * @method static BN_BD() Returns the BN_BD enum instance
 * @method static BN_IN() Returns the BN_IN enum instance
 * @method static BS_BA() Returns the BS_BA enum instance
 * @method static BS_CYRL_BA() Returns the BS_CYRL_BA enum instance
 * @method static BS_CYRL() Returns the BS_CYRL enum instance
 * @method static BS_LATN_BA() Returns the BS_LATN_BA enum instance
 * @method static BS_LATN() Returns the BS_LATN enum instance
 * @method static BR_FR() Returns the BR_FR enum instance
 * @method static BG_BG() Returns the BG_BG enum instance
 * @method static MY() Returns the MY enum instance
 * @method static MY_MM() Returns the MY_MM enum instance
 * @method static CA_AD() Returns the CA_AD enum instance
 * @method static CA_FR() Returns the CA_FR enum instance
 * @method static CA_IT() Returns the CA_IT enum instance
 * @method static CA_ES() Returns the CA_ES enum instance
 * @method static ZH() Returns the ZH enum instance
 * @method static ZH_CN() Returns the ZH_CN enum instance
 * @method static ZH_HK() Returns the ZH_HK enum instance
 * @method static ZH_MO() Returns the ZH_MO enum instance
 * @method static ZH_HANS_CN() Returns the ZH_HANS_CN enum instance
 * @method static ZH_HANS_HK() Returns the ZH_HANS_HK enum instance
 * @method static ZH_HANS_MO() Returns the ZH_HANS_MO enum instance
 * @method static ZH_HANS_SG() Returns the ZH_HANS_SG enum instance
 * @method static ZH_HANS() Returns the ZH_HANS enum instance
 * @method static ZH_SG() Returns the ZH_SG enum instance
 * @method static ZH_TW() Returns the ZH_TW enum instance
 * @method static ZH_HANT_HK() Returns the ZH_HANT_HK enum instance
 * @method static ZH_HANT_MO() Returns the ZH_HANT_MO enum instance
 * @method static ZH_HANT_TW() Returns the ZH_HANT_TW enum instance
 * @method static ZH_HANT() Returns the ZH_HANT enum instance
 * @method static KW() Returns the KW enum instance
 * @method static KW_GB() Returns the KW_GB enum instance
 * @method static HR_BA() Returns the HR_BA enum instance
 * @method static HR_HR() Returns the HR_HR enum instance
 * @method static CS_CZ() Returns the CS_CZ enum instance
 * @method static DA_DK() Returns the DA_DK enum instance
 * @method static DA_GL() Returns the DA_GL enum instance
 * @method static NL_AW() Returns the NL_AW enum instance
 * @method static NL_BE() Returns the NL_BE enum instance
 * @method static NL_BQ() Returns the NL_BQ enum instance
 * @method static NL_CW() Returns the NL_CW enum instance
 * @method static NL_NL() Returns the NL_NL enum instance
 * @method static NL_SX() Returns the NL_SX enum instance
 * @method static NL_SR() Returns the NL_SR enum instance
 * @method static DZ() Returns the DZ enum instance
 * @method static DZ_BT() Returns the DZ_BT enum instance
 * @method static EN_AS() Returns the EN_AS enum instance
 * @method static EN_AI() Returns the EN_AI enum instance
 * @method static EN_AG() Returns the EN_AG enum instance
 * @method static EN_AU() Returns the EN_AU enum instance
 * @method static EN_BS() Returns the EN_BS enum instance
 * @method static EN_BB() Returns the EN_BB enum instance
 * @method static EN_BE() Returns the EN_BE enum instance
 * @method static EN_BZ() Returns the EN_BZ enum instance
 * @method static EN_BM() Returns the EN_BM enum instance
 * @method static EN_BW() Returns the EN_BW enum instance
 * @method static EN_IO() Returns the EN_IO enum instance
 * @method static EN_VG() Returns the EN_VG enum instance
 * @method static EN_CM() Returns the EN_CM enum instance
 * @method static EN_CA() Returns the EN_CA enum instance
 * @method static EN_KY() Returns the EN_KY enum instance
 * @method static EN_CX() Returns the EN_CX enum instance
 * @method static EN_CC() Returns the EN_CC enum instance
 * @method static EN_CK() Returns the EN_CK enum instance
 * @method static EN_DG() Returns the EN_DG enum instance
 * @method static EN_DM() Returns the EN_DM enum instance
 * @method static EN_ER() Returns the EN_ER enum instance
 * @method static EN_FK() Returns the EN_FK enum instance
 * @method static EN_FJ() Returns the EN_FJ enum instance
 * @method static EN_GM() Returns the EN_GM enum instance
 * @method static EN_GH() Returns the EN_GH enum instance
 * @method static EN_GI() Returns the EN_GI enum instance
 * @method static EN_GD() Returns the EN_GD enum instance
 * @method static EN_GU() Returns the EN_GU enum instance
 * @method static EN_GG() Returns the EN_GG enum instance
 * @method static EN_GY() Returns the EN_GY enum instance
 * @method static EN_HK() Returns the EN_HK enum instance
 * @method static EN_IN() Returns the EN_IN enum instance
 * @method static EN_IE() Returns the EN_IE enum instance
 * @method static EN_IM() Returns the EN_IM enum instance
 * @method static EN_JM() Returns the EN_JM enum instance
 * @method static EN_JE() Returns the EN_JE enum instance
 * @method static EN_KE() Returns the EN_KE enum instance
 * @method static EN_KI() Returns the EN_KI enum instance
 * @method static EN_LS() Returns the EN_LS enum instance
 * @method static EN_LR() Returns the EN_LR enum instance
 * @method static EN_MO() Returns the EN_MO enum instance
 * @method static EN_MG() Returns the EN_MG enum instance
 * @method static EN_MW() Returns the EN_MW enum instance
 * @method static EN_MY() Returns the EN_MY enum instance
 * @method static EN_MT() Returns the EN_MT enum instance
 * @method static EN_MH() Returns the EN_MH enum instance
 * @method static EN_MU() Returns the EN_MU enum instance
 * @method static EN_FM() Returns the EN_FM enum instance
 * @method static EN_MS() Returns the EN_MS enum instance
 * @method static EN_NA() Returns the EN_NA enum instance
 * @method static EN_NR() Returns the EN_NR enum instance
 * @method static EN_NZ() Returns the EN_NZ enum instance
 * @method static EN_NG() Returns the EN_NG enum instance
 * @method static EN_NU() Returns the EN_NU enum instance
 * @method static EN_NF() Returns the EN_NF enum instance
 * @method static EN_MP() Returns the EN_MP enum instance
 * @method static EN_PK() Returns the EN_PK enum instance
 * @method static EN_PW() Returns the EN_PW enum instance
 * @method static EN_PG() Returns the EN_PG enum instance
 * @method static EN_PH() Returns the EN_PH enum instance
 * @method static EN_PN() Returns the EN_PN enum instance
 * @method static EN_PR() Returns the EN_PR enum instance
 * @method static EN_WS() Returns the EN_WS enum instance
 * @method static EN_SC() Returns the EN_SC enum instance
 * @method static EN_SL() Returns the EN_SL enum instance
 * @method static EN_SG() Returns the EN_SG enum instance
 * @method static EN_SX() Returns the EN_SX enum instance
 * @method static EN_SB() Returns the EN_SB enum instance
 * @method static EN_ZA() Returns the EN_ZA enum instance
 * @method static EN_SS() Returns the EN_SS enum instance
 * @method static EN_SH() Returns the EN_SH enum instance
 * @method static EN_KN() Returns the EN_KN enum instance
 * @method static EN_LC() Returns the EN_LC enum instance
 * @method static EN_VC() Returns the EN_VC enum instance
 * @method static EN_SD() Returns the EN_SD enum instance
 * @method static EN_SZ() Returns the EN_SZ enum instance
 * @method static EN_TZ() Returns the EN_TZ enum instance
 * @method static EN_TK() Returns the EN_TK enum instance
 * @method static EN_TT() Returns the EN_TT enum instance
 * @method static EN_TC() Returns the EN_TC enum instance
 * @method static EN_TV() Returns the EN_TV enum instance
 * @method static EN_UM() Returns the EN_UM enum instance
 * @method static EN_VI() Returns the EN_VI enum instance
 * @method static EN_UG() Returns the EN_UG enum instance
 * @method static EN_GB() Returns the EN_GB enum instance
 * @method static EN_US() Returns the EN_US enum instance
 * @method static EN_VU() Returns the EN_VU enum instance
 * @method static EN_ZM() Returns the EN_ZM enum instance
 * @method static EN_ZW() Returns the EN_ZW enum instance
 * @method static ET_EE() Returns the ET_EE enum instance
 * @method static EE() Returns the EE enum instance
 * @method static EE_GH() Returns the EE_GH enum instance
 * @method static EE_TG() Returns the EE_TG enum instance
 * @method static FO_FO() Returns the FO_FO enum instance
 * @method static FI_FI() Returns the FI_FI enum instance
 * @method static FR_DZ() Returns the FR_DZ enum instance
 * @method static FR_BE() Returns the FR_BE enum instance
 * @method static FR_BJ() Returns the FR_BJ enum instance
 * @method static FR_BF() Returns the FR_BF enum instance
 * @method static FR_BI() Returns the FR_BI enum instance
 * @method static FR_CM() Returns the FR_CM enum instance
 * @method static FR_CA() Returns the FR_CA enum instance
 * @method static FR_CF() Returns the FR_CF enum instance
 * @method static FR_TD() Returns the FR_TD enum instance
 * @method static FR_KM() Returns the FR_KM enum instance
 * @method static FR_CG() Returns the FR_CG enum instance
 * @method static FR_CD() Returns the FR_CD enum instance
 * @method static FR_CI() Returns the FR_CI enum instance
 * @method static FR_DJ() Returns the FR_DJ enum instance
 * @method static FR_GQ() Returns the FR_GQ enum instance
 * @method static FR_FR() Returns the FR_FR enum instance
 * @method static FR_GF() Returns the FR_GF enum instance
 * @method static FR_PF() Returns the FR_PF enum instance
 * @method static FR_GA() Returns the FR_GA enum instance
 * @method static FR_GP() Returns the FR_GP enum instance
 * @method static FR_GN() Returns the FR_GN enum instance
 * @method static FR_HT() Returns the FR_HT enum instance
 * @method static FR_LU() Returns the FR_LU enum instance
 * @method static FR_MG() Returns the FR_MG enum instance
 * @method static FR_ML() Returns the FR_ML enum instance
 * @method static FR_MQ() Returns the FR_MQ enum instance
 * @method static FR_MR() Returns the FR_MR enum instance
 * @method static FR_MU() Returns the FR_MU enum instance
 * @method static FR_YT() Returns the FR_YT enum instance
 * @method static FR_MC() Returns the FR_MC enum instance
 * @method static FR_MA() Returns the FR_MA enum instance
 * @method static FR_NC() Returns the FR_NC enum instance
 * @method static FR_NE() Returns the FR_NE enum instance
 * @method static FR_RE() Returns the FR_RE enum instance
 * @method static FR_SN() Returns the FR_SN enum instance
 * @method static FR_SC() Returns the FR_SC enum instance
 * @method static FR_BL() Returns the FR_BL enum instance
 * @method static FR_MF() Returns the FR_MF enum instance
 * @method static FR_PM() Returns the FR_PM enum instance
 * @method static FR_CH() Returns the FR_CH enum instance
 * @method static FR_SY() Returns the FR_SY enum instance
 * @method static FR_TG() Returns the FR_TG enum instance
 * @method static FR_TN() Returns the FR_TN enum instance
 * @method static FR_VU() Returns the FR_VU enum instance
 * @method static FR_WF() Returns the FR_WF enum instance
 * @method static FF() Returns the FF enum instance
 * @method static FF_CM() Returns the FF_CM enum instance
 * @method static FF_GN() Returns the FF_GN enum instance
 * @method static FF_MR() Returns the FF_MR enum instance
 * @method static FF_SN() Returns the FF_SN enum instance
 * @method static GL_ES() Returns the GL_ES enum instance
 * @method static LG() Returns the LG enum instance
 * @method static LG_UG() Returns the LG_UG enum instance
 * @method static KA_GE() Returns the KA_GE enum instance
 * @method static DE_AT() Returns the DE_AT enum instance
 * @method static DE_BE() Returns the DE_BE enum instance
 * @method static DE_DE() Returns the DE_DE enum instance
 * @method static DE_LI() Returns the DE_LI enum instance
 * @method static DE_LU() Returns the DE_LU enum instance
 * @method static DE_CH() Returns the DE_CH enum instance
 * @method static EL_CY() Returns the EL_CY enum instance
 * @method static EL_GR() Returns the EL_GR enum instance
 * @method static GU_IN() Returns the GU_IN enum instance
 * @method static HA() Returns the HA enum instance
 * @method static HA_GH() Returns the HA_GH enum instance
 * @method static HA_LATN_GH() Returns the HA_LATN_GH enum instance
 * @method static HA_LATN_NE() Returns the HA_LATN_NE enum instance
 * @method static HA_LATN_NG() Returns the HA_LATN_NG enum instance
 * @method static HA_LATN() Returns the HA_LATN enum instance
 * @method static HA_NE() Returns the HA_NE enum instance
 * @method static HA_NG() Returns the HA_NG enum instance
 * @method static HE_IL() Returns the HE_IL enum instance
 * @method static HI_IN() Returns the HI_IN enum instance
 * @method static HU_HU() Returns the HU_HU enum instance
 * @method static IS_IS() Returns the IS_IS enum instance
 * @method static SQ() Returns the SQ enum instance
 * @method static AR() Returns the AR enum instance
 * @method static AS() Returns the AS enum instance
 * @method static ES() Returns the ES enum instance
 * @method static EO() Returns the EO enum instance
 * @method static ET() Returns the ET enum instance
 * @method static EN() Returns the EN enum instance
 * @method static EN_TO() Returns the EN_TO enum instance
 * @method static EN_RW() Returns the EN_RW enum instance
 * @method static IG() Returns the IG enum instance
 * @method static IG_NG() Returns the IG_NG enum instance
 * @method static CS() Returns the CS enum instance
 * @method static FR() Returns the FR enum instance
 * @method static FR_RW() Returns the FR_RW enum instance
 * @method static FI() Returns the FI enum instance
 * @method static FY() Returns the FY enum instance
 * @method static HE() Returns the HE enum instance
 * @method static HI() Returns the HI enum instance
 * @method static HU() Returns the HU enum instance
 * @method static KM() Returns the KM enum instance
 * @method static KN() Returns the KN enum instance
 * @method static CA() Returns the CA enum instance
 * @method static KO() Returns the KO enum instance
 * @method static HR() Returns the HR enum instance
 * @method static PL() Returns the PL enum instance
 * @method static PT() Returns the PT enum instance
 * @method static PA() Returns the PA enum instance
 * @method static SR() Returns the SR enum instance
 * @method static IS() Returns the IS enum instance
 * @method static SK() Returns the SK enum instance
 * @method static SO() Returns the SO enum instance
 * @method static SV() Returns the SV enum instance
 * @method static SW() Returns the SW enum instance
 * @method static IT() Returns the IT enum instance
 * @method static TA() Returns the TA enum instance
 * @method static TH() Returns the TH enum instance
 * @method static TE() Returns the TE enum instance
 * @method static TR() Returns the TR enum instance
 * @method static EU() Returns the EU enum instance
 * @method static BE() Returns the BE enum instance
 * @method static BN() Returns the BN enum instance
 * @method static DE() Returns the DE enum instance
 * @method static DA() Returns the DA enum instance
 * @method static GL() Returns the GL enum instance
 * @method static CY() Returns the CY enum instance
 * @method static GD() Returns the GD enum instance
 * @method static EL() Returns the EL enum instance
 * @method static LO() Returns the LO enum instance
 * @method static LT() Returns the LT enum instance
 * @method static ML() Returns the ML enum instance
 * @method static MS() Returns the MS enum instance
 * @method static MT() Returns the MT enum instance
 * @method static MR() Returns the MR enum instance
 * @method static MK() Returns the MK enum instance
 * @method static MN() Returns the MN enum instance
 * @method static NE() Returns the NE enum instance
 * @method static NL() Returns the NL enum instance
 * @method static NO() Returns the NO enum instance
 * @method static AF() Returns the AF enum instance
 * @method static LV() Returns the LV enum instance
 * @method static RO() Returns the RO enum instance
 * @method static HY() Returns the HY enum instance
 * @method static SL() Returns the SL enum instance
 * @method static VI() Returns the VI enum instance
 * @method static UK() Returns the UK enum instance
 * @method static ID() Returns the ID enum instance
 * @method static GA() Returns the GA enum instance
 * @method static RU() Returns the RU enum instance
 * @method static UG() Returns the UG enum instance
 * @method static JA() Returns the JA enum instance
 * @method static LN() Returns the LN enum instance
 * @method static PS() Returns the PS enum instance
 * @method static ID_ID() Returns the ID_ID enum instance
 * @method static KY() Returns the KY enum instance
 * @method static AM() Returns the AM enum instance
 * @method static TI() Returns the TI enum instance
 * @method static AZ() Returns the AZ enum instance
 * @method static BR() Returns the BR enum instance
 * @method static BS() Returns the BS enum instance
 * @method static FO() Returns the FO enum instance
 * @method static GU() Returns the GU enum instance
 * @method static KA() Returns the KA enum instance
 * @method static NN() Returns the NN enum instance
 * @method static FA() Returns the FA enum instance
 * @method static SH() Returns the SH enum instance
 * @method static SI() Returns the SI enum instance
 * @method static YI() Returns the YI enum instance
 * @method static UR() Returns the UR enum instance
 * @method static UZ() Returns the UZ enum instance
 * @method static ZU() Returns the ZU enum instance
 * @method static OR() Returns the OR enum instance
 * @method static GA_IE() Returns the GA_IE enum instance
 * @method static IT_IT() Returns the IT_IT enum instance
 * @method static IT_SM() Returns the IT_SM enum instance
 * @method static IT_CH() Returns the IT_CH enum instance
 * @method static JA_JP() Returns the JA_JP enum instance
 * @method static KL() Returns the KL enum instance
 * @method static KL_GL() Returns the KL_GL enum instance
 * @method static KN_IN() Returns the KN_IN enum instance
 * @method static KS() Returns the KS enum instance
 * @method static KS_ARAB_IN() Returns the KS_ARAB_IN enum instance
 * @method static KS_ARAB() Returns the KS_ARAB enum instance
 * @method static KS_IN() Returns the KS_IN enum instance
 * @method static KK() Returns the KK enum instance
 * @method static KK_CYRL_KZ() Returns the KK_CYRL_KZ enum instance
 * @method static KK_CYRL() Returns the KK_CYRL enum instance
 * @method static KK_KZ() Returns the KK_KZ enum instance
 * @method static KM_KH() Returns the KM_KH enum instance
 * @method static KI() Returns the KI enum instance
 * @method static KI_KE() Returns the KI_KE enum instance
 * @method static RW() Returns the RW enum instance
 * @method static RW_RW() Returns the RW_RW enum instance
 * @method static KO_KP() Returns the KO_KP enum instance
 * @method static KO_KR() Returns the KO_KR enum instance
 * @method static KY_CYRL_KG() Returns the KY_CYRL_KG enum instance
 * @method static KY_CYRL() Returns the KY_CYRL enum instance
 * @method static KY_KG() Returns the KY_KG enum instance
 * @method static LO_LA() Returns the LO_LA enum instance
 * @method static LV_LV() Returns the LV_LV enum instance
 * @method static LN_AO() Returns the LN_AO enum instance
 * @method static LN_CF() Returns the LN_CF enum instance
 * @method static LN_CG() Returns the LN_CG enum instance
 * @method static LN_CD() Returns the LN_CD enum instance
 * @method static LT_LT() Returns the LT_LT enum instance
 * @method static LU() Returns the LU enum instance
 * @method static LU_CD() Returns the LU_CD enum instance
 * @method static LB() Returns the LB enum instance
 * @method static LB_LU() Returns the LB_LU enum instance
 * @method static MK_MK() Returns the MK_MK enum instance
 * @method static MG() Returns the MG enum instance
 * @method static MG_MG() Returns the MG_MG enum instance
 * @method static MS_BN() Returns the MS_BN enum instance
 * @method static MS_LATN_BN() Returns the MS_LATN_BN enum instance
 * @method static MS_LATN_MY() Returns the MS_LATN_MY enum instance
 * @method static MS_LATN_SG() Returns the MS_LATN_SG enum instance
 * @method static MS_LATN() Returns the MS_LATN enum instance
 * @method static MS_MY() Returns the MS_MY enum instance
 * @method static MS_SG() Returns the MS_SG enum instance
 * @method static ML_IN() Returns the ML_IN enum instance
 * @method static MT_MT() Returns the MT_MT enum instance
 * @method static GV() Returns the GV enum instance
 * @method static GV_IM() Returns the GV_IM enum instance
 * @method static MR_IN() Returns the MR_IN enum instance
 * @method static MN_CYRL_MN() Returns the MN_CYRL_MN enum instance
 * @method static MN_CYRL() Returns the MN_CYRL enum instance
 * @method static MN_MN() Returns the MN_MN enum instance
 * @method static NE_IN() Returns the NE_IN enum instance
 * @method static NE_NP() Returns the NE_NP enum instance
 * @method static ND() Returns the ND enum instance
 * @method static ND_ZW() Returns the ND_ZW enum instance
 * @method static SE() Returns the SE enum instance
 * @method static SE_FI() Returns the SE_FI enum instance
 * @method static SE_NO() Returns the SE_NO enum instance
 * @method static SE_SE() Returns the SE_SE enum instance
 * @method static NO_NO() Returns the NO_NO enum instance
 * @method static NB() Returns the NB enum instance
 * @method static NB_NO() Returns the NB_NO enum instance
 * @method static NB_SJ() Returns the NB_SJ enum instance
 * @method static NN_NO() Returns the NN_NO enum instance
 * @method static OR_IN() Returns the OR_IN enum instance
 * @method static OM() Returns the OM enum instance
 * @method static OM_ET() Returns the OM_ET enum instance
 * @method static OM_KE() Returns the OM_KE enum instance
 * @method static OS() Returns the OS enum instance
 * @method static OS_GE() Returns the OS_GE enum instance
 * @method static OS_RU() Returns the OS_RU enum instance
 * @method static PS_AF() Returns the PS_AF enum instance
 * @method static FA_AF() Returns the FA_AF enum instance
 * @method static FA_IR() Returns the FA_IR enum instance
 * @method static PL_PL() Returns the PL_PL enum instance
 * @method static PT_AO() Returns the PT_AO enum instance
 * @method static PT_BR() Returns the PT_BR enum instance
 * @method static PT_CV() Returns the PT_CV enum instance
 * @method static PT_GW() Returns the PT_GW enum instance
 * @method static PT_MO() Returns the PT_MO enum instance
 * @method static PT_MZ() Returns the PT_MZ enum instance
 * @method static PT_PT() Returns the PT_PT enum instance
 * @method static PT_ST() Returns the PT_ST enum instance
 * @method static PT_TL() Returns the PT_TL enum instance
 * @method static PA_ARAB_PK() Returns the PA_ARAB_PK enum instance
 * @method static PA_ARAB() Returns the PA_ARAB enum instance
 * @method static PA_GURU_IN() Returns the PA_GURU_IN enum instance
 * @method static PA_GURU() Returns the PA_GURU enum instance
 * @method static PA_IN() Returns the PA_IN enum instance
 * @method static PA_PK() Returns the PA_PK enum instance
 * @method static QU() Returns the QU enum instance
 * @method static QU_BO() Returns the QU_BO enum instance
 * @method static QU_EC() Returns the QU_EC enum instance
 * @method static QU_PE() Returns the QU_PE enum instance
 * @method static RO_MD() Returns the RO_MD enum instance
 * @method static RO_RO() Returns the RO_RO enum instance
 * @method static RM() Returns the RM enum instance
 * @method static RM_CH() Returns the RM_CH enum instance
 * @method static RN() Returns the RN enum instance
 * @method static RN_BI() Returns the RN_BI enum instance
 * @method static RU_BY() Returns the RU_BY enum instance
 * @method static RU_KZ() Returns the RU_KZ enum instance
 * @method static RU_KG() Returns the RU_KG enum instance
 * @method static RU_MD() Returns the RU_MD enum instance
 * @method static RU_RU() Returns the RU_RU enum instance
 * @method static RU_UA() Returns the RU_UA enum instance
 * @method static SG() Returns the SG enum instance
 * @method static SG_CF() Returns the SG_CF enum instance
 * @method static GD_GB() Returns the GD_GB enum instance
 * @method static SR_BA() Returns the SR_BA enum instance
 * @method static SR_CYRL_BA() Returns the SR_CYRL_BA enum instance
 * @method static SR_CYRL_XK() Returns the SR_CYRL_XK enum instance
 * @method static SR_CYRL_ME() Returns the SR_CYRL_ME enum instance
 * @method static SR_CYRL_RS() Returns the SR_CYRL_RS enum instance
 * @method static SR_CYRL() Returns the SR_CYRL enum instance
 * @method static SR_XK() Returns the SR_XK enum instance
 * @method static SR_LATN_BA() Returns the SR_LATN_BA enum instance
 * @method static SR_LATN_XK() Returns the SR_LATN_XK enum instance
 * @method static SR_LATN_ME() Returns the SR_LATN_ME enum instance
 * @method static SR_LATN_RS() Returns the SR_LATN_RS enum instance
 * @method static SR_LATN() Returns the SR_LATN enum instance
 * @method static SR_ME() Returns the SR_ME enum instance
 * @method static SR_RS() Returns the SR_RS enum instance
 * @method static SH_BA() Returns the SH_BA enum instance
 * @method static SN() Returns the SN enum instance
 * @method static SN_ZW() Returns the SN_ZW enum instance
 * @method static II() Returns the II enum instance
 * @method static II_CN() Returns the II_CN enum instance
 * @method static SI_LK() Returns the SI_LK enum instance
 * @method static SK_SK() Returns the SK_SK enum instance
 * @method static SL_SI() Returns the SL_SI enum instance
 * @method static SO_DJ() Returns the SO_DJ enum instance
 * @method static SO_ET() Returns the SO_ET enum instance
 * @method static SO_KE() Returns the SO_KE enum instance
 * @method static SO_SO() Returns the SO_SO enum instance
 * @method static ES_AR() Returns the ES_AR enum instance
 * @method static ES_BO() Returns the ES_BO enum instance
 * @method static ES_IC() Returns the ES_IC enum instance
 * @method static ES_EA() Returns the ES_EA enum instance
 * @method static ES_CL() Returns the ES_CL enum instance
 * @method static ES_CO() Returns the ES_CO enum instance
 * @method static ES_CR() Returns the ES_CR enum instance
 * @method static ES_CU() Returns the ES_CU enum instance
 * @method static ES_DO() Returns the ES_DO enum instance
 * @method static ES_EC() Returns the ES_EC enum instance
 * @method static ES_SV() Returns the ES_SV enum instance
 * @method static ES_GQ() Returns the ES_GQ enum instance
 * @method static ES_GT() Returns the ES_GT enum instance
 * @method static ES_HN() Returns the ES_HN enum instance
 * @method static ES_MX() Returns the ES_MX enum instance
 * @method static ES_NI() Returns the ES_NI enum instance
 * @method static ES_PA() Returns the ES_PA enum instance
 * @method static ES_PY() Returns the ES_PY enum instance
 * @method static ES_PE() Returns the ES_PE enum instance
 * @method static ES_PH() Returns the ES_PH enum instance
 * @method static ES_PR() Returns the ES_PR enum instance
 * @method static ES_ES() Returns the ES_ES enum instance
 * @method static ES_US() Returns the ES_US enum instance
 * @method static ES_UY() Returns the ES_UY enum instance
 * @method static ES_VE() Returns the ES_VE enum instance
 * @method static SW_KE() Returns the SW_KE enum instance
 * @method static SW_TZ() Returns the SW_TZ enum instance
 * @method static SW_UG() Returns the SW_UG enum instance
 * @method static SV_AX() Returns the SV_AX enum instance
 * @method static SV_FI() Returns the SV_FI enum instance
 * @method static SV_SE() Returns the SV_SE enum instance
 * @method static TL() Returns the TL enum instance
 * @method static TL_PH() Returns the TL_PH enum instance
 * @method static TA_IN() Returns the TA_IN enum instance
 * @method static TA_MY() Returns the TA_MY enum instance
 * @method static TA_SG() Returns the TA_SG enum instance
 * @method static TA_LK() Returns the TA_LK enum instance
 * @method static TE_IN() Returns the TE_IN enum instance
 * @method static TH_TH() Returns the TH_TH enum instance
 * @method static BO() Returns the BO enum instance
 * @method static BO_CN() Returns the BO_CN enum instance
 * @method static BO_IN() Returns the BO_IN enum instance
 * @method static TI_ER() Returns the TI_ER enum instance
 * @method static TI_ET() Returns the TI_ET enum instance
 * @method static TO() Returns the TO enum instance
 * @method static TO_TO() Returns the TO_TO enum instance
 * @method static TR_CY() Returns the TR_CY enum instance
 * @method static TR_TR() Returns the TR_TR enum instance
 * @method static UK_UA() Returns the UK_UA enum instance
 * @method static UR_IN() Returns the UR_IN enum instance
 * @method static UR_PK() Returns the UR_PK enum instance
 * @method static BG() Returns the BG enum instance
 * @method static UG_ARAB_CN() Returns the UG_ARAB_CN enum instance
 * @method static UG_ARAB() Returns the UG_ARAB enum instance
 * @method static UG_CN() Returns the UG_CN enum instance
 * @method static UZ_AF() Returns the UZ_AF enum instance
 * @method static UZ_ARAB_AF() Returns the UZ_ARAB_AF enum instance
 * @method static UZ_ARAB() Returns the UZ_ARAB enum instance
 * @method static UZ_CYRL_UZ() Returns the UZ_CYRL_UZ enum instance
 * @method static UZ_CYRL() Returns the UZ_CYRL enum instance
 * @method static UZ_LATN_UZ() Returns the UZ_LATN_UZ enum instance
 * @method static UZ_LATN() Returns the UZ_LATN enum instance
 * @method static UZ_UZ() Returns the UZ_UZ enum instance
 * @method static VI_VN() Returns the VI_VN enum instance
 * @method static CY_GB() Returns the CY_GB enum instance
 * @method static FY_NL() Returns the FY_NL enum instance
 * @method static YO() Returns the YO enum instance
 * @method static YO_BJ() Returns the YO_BJ enum instance
 * @method static YO_NG() Returns the YO_NG enum instance
 * @method static ZU_ZA() Returns the ZU_ZA enum instance
 */
enum Locale: string
{
    use Enum;

    /**
     * Afrikaans (Namibia).
     */
    #[Label('Afrikaans (Namibia)')]
    case AF_NA = 'af_NA';

    /**
     * Afrikaans (South Africa).
     */
    #[Label('Afrikaans (South Africa)')]
    case AF_ZA = 'af_ZA';

    /**
     * Akan.
     */
    #[Label('Akan')]
    case AK = 'ak';

    /**
     * Akan (Ghana).
     */
    #[Label('Akan (Ghana)')]
    case AK_GH = 'ak_GH';

    /**
     * Albanian (Albania).
     */
    #[Label('Albanian (Albania)')]
    case SQ_AL = 'sq_AL';

    /**
     * Albanian (Kosovo).
     */
    #[Label('Albanian (Kosovo)')]
    case SQ_XK = 'sq_XK';

    /**
     * Albanian (Macedonia).
     */
    #[Label('Albanian (Macedonia)')]
    case SQ_MK = 'sq_MK';

    /**
     * Amharic (Ethiopia).
     */
    #[Label('Amharic (Ethiopia)')]
    case AM_ET = 'am_ET';

    /**
     * Arabic (Algeria).
     */
    #[Label('Arabic (Algeria)')]
    case AR_DZ = 'ar_DZ';

    /**
     * Arabic (Bahrain).
     */
    #[Label('Arabic (Bahrain)')]
    case AR_BH = 'ar_BH';

    /**
     * Arabic (Chad).
     */
    #[Label('Arabic (Chad)')]
    case AR_TD = 'ar_TD';

    /**
     * Arabic (Comoros).
     */
    #[Label('Arabic (Comoros)')]
    case AR_KM = 'ar_KM';

    /**
     * Arabic (Djibouti).
     */
    #[Label('Arabic (Djibouti)')]
    case AR_DJ = 'ar_DJ';

    /**
     * Arabic (Egypt).
     */
    #[Label('Arabic (Egypt)')]
    case AR_EG = 'ar_EG';

    /**
     * Arabic (Eritrea).
     */
    #[Label('Arabic (Eritrea)')]
    case AR_ER = 'ar_ER';

    /**
     * Arabic (Iraq).
     */
    #[Label('Arabic (Iraq)')]
    case AR_IQ = 'ar_IQ';

    /**
     * Arabic (Israel).
     */
    #[Label('Arabic (Israel)')]
    case AR_IL = 'ar_IL';

    /**
     * Arabic (Jordan).
     */
    #[Label('Arabic (Jordan)')]
    case AR_JO = 'ar_JO';

    /**
     * Arabic (Kuwait).
     */
    #[Label('Arabic (Kuwait)')]
    case AR_KW = 'ar_KW';

    /**
     * Arabic (Lebanon).
     */
    #[Label('Arabic (Lebanon)')]
    case AR_LB = 'ar_LB';

    /**
     * Arabic (Libya).
     */
    #[Label('Arabic (Libya)')]
    case AR_LY = 'ar_LY';

    /**
     * Arabic (Mauritania).
     */
    #[Label('Arabic (Mauritania)')]
    case AR_MR = 'ar_MR';

    /**
     * Arabic (Morocco).
     */
    #[Label('Arabic (Morocco)')]
    case AR_MA = 'ar_MA';

    /**
     * Arabic (Oman).
     */
    #[Label('Arabic (Oman)')]
    case AR_OM = 'ar_OM';

    /**
     * Arabic (Palestinian Territories).
     */
    #[Label('Arabic (Palestinian Territories)')]
    case AR_PS = 'ar_PS';

    /**
     * Arabic (Qatar).
     */
    #[Label('Arabic (Qatar)')]
    case AR_QA = 'ar_QA';

    /**
     * Arabic (Saudi Arabia).
     */
    #[Label('Arabic (Saudi Arabia)')]
    case AR_SA = 'ar_SA';

    /**
     * Arabic (Somalia).
     */
    #[Label('Arabic (Somalia)')]
    case AR_SO = 'ar_SO';

    /**
     * Arabic (South Sudan).
     */
    #[Label('Arabic (South Sudan)')]
    case AR_SS = 'ar_SS';

    /**
     * Arabic (Sudan).
     */
    #[Label('Arabic (Sudan)')]
    case AR_SD = 'ar_SD';

    /**
     * Arabic (Syria).
     */
    #[Label('Arabic (Syria)')]
    case AR_SY = 'ar_SY';

    /**
     * Arabic (Tunisia).
     */
    #[Label('Arabic (Tunisia)')]
    case AR_TN = 'ar_TN';

    /**
     * Arabic (United Arab Emirates).
     */
    #[Label('Arabic (United Arab Emirates)')]
    case AR_AE = 'ar_AE';

    /**
     * Arabic (Western Sahara).
     */
    #[Label('Arabic (Western Sahara)')]
    case AR_EH = 'ar_EH';

    /**
     * Arabic (Yemen).
     */
    #[Label('Arabic (Yemen)')]
    case AR_YE = 'ar_YE';

    /**
     * Armenian (Armenia).
     */
    #[Label('Armenian (Armenia)')]
    case HY_AM = 'hy_AM';

    /**
     * Assamese (India).
     */
    #[Label('Assamese (India)')]
    case AS_IN = 'as_IN';

    /**
     * Azerbaijani (Azerbaijan).
     */
    #[Label('Azerbaijani (Azerbaijan)')]
    case AZ_AZ = 'az_AZ';

    /**
     * Azerbaijani (Cyrillic, Azerbaijan).
     */
    #[Label('Azerbaijani (Cyrillic, Azerbaijan)')]
    case AZ_CYRL_AZ = 'az_Cyrl_AZ';

    /**
     * Azerbaijani (Cyrillic).
     */
    #[Label('Azerbaijani (Cyrillic)')]
    case AZ_CYRL = 'az_Cyrl';

    /**
     * Azerbaijani (Latin, Azerbaijan).
     */
    #[Label('Azerbaijani (Latin, Azerbaijan)')]
    case AZ_LATN_AZ = 'az_Latn_AZ';

    /**
     * Azerbaijani (Latin).
     */
    #[Label('Azerbaijani (Latin)')]
    case AZ_LATN = 'az_Latn';

    /**
     * Bambara.
     */
    #[Label('Bambara')]
    case BM = 'bm';

    /**
     * Bambara (Latin, Mali).
     */
    #[Label('Bambara (Latin, Mali)')]
    case BM_LATN_ML = 'bm_Latn_ML';

    /**
     * Bambara (Latin).
     */
    #[Label('Bambara (Latin)')]
    case BM_LATN = 'bm_Latn';

    /**
     * Basque (Spain).
     */
    #[Label('Basque (Spain)')]
    case EU_ES = 'eu_ES';

    /**
     * Belarusian (Belarus).
     */
    #[Label('Belarusian (Belarus)')]
    case BE_BY = 'be_BY';

    /**
     * Bengali (Bangladesh).
     */
    #[Label('Bengali (Bangladesh)')]
    case BN_BD = 'bn_BD';

    /**
     * Bengali (India).
     */
    #[Label('Bengali (India)')]
    case BN_IN = 'bn_IN';

    /**
     * Bosnian (Bosnia & Herzegovina).
     */
    #[Label('Bosnian (Bosnia & Herzegovina)')]
    case BS_BA = 'bs_BA';

    /**
     * Bosnian (Cyrillic, Bosnia & Herzegovina).
     */
    #[Label('Bosnian (Cyrillic, Bosnia & Herzegovina)')]
    case BS_CYRL_BA = 'bs_Cyrl_BA';

    /**
     * Bosnian (Cyrillic).
     */
    #[Label('Bosnian (Cyrillic)')]
    case BS_CYRL = 'bs_Cyrl';

    /**
     * Bosnian (Latin, Bosnia & Herzegovina).
     */
    #[Label('Bosnian (Latin, Bosnia & Herzegovina)')]
    case BS_LATN_BA = 'bs_Latn_BA';

    /**
     * Bosnian (Latin).
     */
    #[Label('Bosnian (Latin)')]
    case BS_LATN = 'bs_Latn';

    /**
     * Breton (France).
     */
    #[Label('Breton (France)')]
    case BR_FR = 'br_FR';

    /**
     * Bulgarian (Bulgaria).
     */
    #[Label('Bulgarian (Bulgaria)')]
    case BG_BG = 'bg_BG';

    /**
     * Burmese.
     */
    #[Label('Burmese')]
    case MY = 'my';

    /**
     * Burmese (Myanmar (Burma)).
     */
    #[Label('Burmese (Myanmar (Burma))')]
    case MY_MM = 'my_MM';

    /**
     * Catalan (Andorra).
     */
    #[Label('Catalan (Andorra)')]
    case CA_AD = 'ca_AD';

    /**
     * Catalan (France).
     */
    #[Label('Catalan (France)')]
    case CA_FR = 'ca_FR';

    /**
     * Catalan (Italy).
     */
    #[Label('Catalan (Italy)')]
    case CA_IT = 'ca_IT';

    /**
     * Catalan (Spain).
     */
    #[Label('Catalan (Spain)')]
    case CA_ES = 'ca_ES';

    /**
     * Chinese.
     */
    #[Label('Chinese')]
    case ZH = 'zh';

    /**
     * Chinese (China).
     */
    #[Label('Chinese (China)')]
    case ZH_CN = 'zh_CN';

    /**
     * Chinese (Hong Kong SAR China).
     */
    #[Label('Chinese (Hong Kong SAR China)')]
    case ZH_HK = 'zh_HK';

    /**
     * Chinese (Macau SAR China).
     */
    #[Label('Chinese (Macau SAR China)')]
    case ZH_MO = 'zh_MO';

    /**
     * Chinese (Simplified, China).
     */
    #[Label('Chinese (Simplified, China)')]
    case ZH_HANS_CN = 'zh_Hans_CN';

    /**
     * Chinese (Simplified, Hong Kong SAR China).
     */
    #[Label('Chinese (Simplified, Hong Kong SAR China)')]
    case ZH_HANS_HK = 'zh_Hans_HK';

    /**
     * Chinese (Simplified, Macau SAR China).
     */
    #[Label('Chinese (Simplified, Macau SAR China)')]
    case ZH_HANS_MO = 'zh_Hans_MO';

    /**
     * Chinese (Simplified, Singapore).
     */
    #[Label('Chinese (Simplified, Singapore)')]
    case ZH_HANS_SG = 'zh_Hans_SG';

    /**
     * Chinese (Simplified).
     */
    #[Label('Chinese (Simplified)')]
    case ZH_HANS = 'zh_Hans';

    /**
     * Chinese (Singapore).
     */
    #[Label('Chinese (Singapore)')]
    case ZH_SG = 'zh_SG';

    /**
     * Chinese (Taiwan).
     */
    #[Label('Chinese (Taiwan)')]
    case ZH_TW = 'zh_TW';

    /**
     * Chinese (Traditional, Hong Kong SAR China).
     */
    #[Label('Chinese (Traditional, Hong Kong SAR China)')]
    case ZH_HANT_HK = 'zh_Hant_HK';

    /**
     * Chinese (Traditional, Macau SAR China).
     */
    #[Label('Chinese (Traditional, Macau SAR China)')]
    case ZH_HANT_MO = 'zh_Hant_MO';

    /**
     * Chinese (Traditional, Taiwan).
     */
    #[Label('Chinese (Traditional, Taiwan)')]
    case ZH_HANT_TW = 'zh_Hant_TW';

    /**
     * Chinese (Traditional).
     */
    #[Label('Chinese (Traditional)')]
    case ZH_HANT = 'zh_Hant';

    /**
     * Cornish.
     */
    #[Label('Cornish')]
    case KW = 'kw';

    /**
     * Cornish (United Kingdom).
     */
    #[Label('Cornish (United Kingdom)')]
    case KW_GB = 'kw_GB';

    /**
     * Croatian (Bosnia & Herzegovina).
     */
    #[Label('Croatian (Bosnia & Herzegovina)')]
    case HR_BA = 'hr_BA';

    /**
     * Croatian (Croatia).
     */
    #[Label('Croatian (Croatia)')]
    case HR_HR = 'hr_HR';

    /**
     * Czech (Czech Republic).
     */
    #[Label('Czech (Czech Republic)')]
    case CS_CZ = 'cs_CZ';

    /**
     * Danish (Denmark).
     */
    #[Label('Danish (Denmark)')]
    case DA_DK = 'da_DK';

    /**
     * Danish (Greenland).
     */
    #[Label('Danish (Greenland)')]
    case DA_GL = 'da_GL';

    /**
     * Dutch (Aruba).
     */
    #[Label('Dutch (Aruba)')]
    case NL_AW = 'nl_AW';

    /**
     * Dutch (Belgium).
     */
    #[Label('Dutch (Belgium)')]
    case NL_BE = 'nl_BE';

    /**
     * Dutch (Caribbean Netherlands).
     */
    #[Label('Dutch (Caribbean Netherlands)')]
    case NL_BQ = 'nl_BQ';

    /**
     * Dutch (Curaçao).
     */
    #[Label('Dutch (Curaçao)')]
    case NL_CW = 'nl_CW';

    /**
     * Dutch (Netherlands).
     */
    #[Label('Dutch (Netherlands)')]
    case NL_NL = 'nl_NL';

    /**
     * Dutch (Sint Maarten).
     */
    #[Label('Dutch (Sint Maarten)')]
    case NL_SX = 'nl_SX';

    /**
     * Dutch (Suriname).
     */
    #[Label('Dutch (Suriname)')]
    case NL_SR = 'nl_SR';

    /**
     * Dzongkha.
     */
    #[Label('Dzongkha')]
    case DZ = 'dz';

    /**
     * Dzongkha (Bhutan).
     */
    #[Label('Dzongkha (Bhutan)')]
    case DZ_BT = 'dz_BT';

    /**
     * English (American Samoa).
     */
    #[Label('English (American Samoa)')]
    case EN_AS = 'en_AS';

    /**
     * English (Anguilla).
     */
    #[Label('English (Anguilla)')]
    case EN_AI = 'en_AI';

    /**
     * English (Antigua & Barbuda).
     */
    #[Label('English (Antigua & Barbuda)')]
    case EN_AG = 'en_AG';

    /**
     * English (Australia).
     */
    #[Label('English (Australia)')]
    case EN_AU = 'en_AU';

    /**
     * English (Bahamas).
     */
    #[Label('English (Bahamas)')]
    case EN_BS = 'en_BS';

    /**
     * English (Barbados).
     */
    #[Label('English (Barbados)')]
    case EN_BB = 'en_BB';

    /**
     * English (Belgium).
     */
    #[Label('English (Belgium)')]
    case EN_BE = 'en_BE';

    /**
     * English (Belize).
     */
    #[Label('English (Belize)')]
    case EN_BZ = 'en_BZ';

    /**
     * English (Bermuda).
     */
    #[Label('English (Bermuda)')]
    case EN_BM = 'en_BM';

    /**
     * English (Botswana).
     */
    #[Label('English (Botswana)')]
    case EN_BW = 'en_BW';

    /**
     * English (British Indian Ocean Territory).
     */
    #[Label('English (British Indian Ocean Territory)')]
    case EN_IO = 'en_IO';

    /**
     * English (British Virgin Islands).
     */
    #[Label('English (British Virgin Islands)')]
    case EN_VG = 'en_VG';

    /**
     * English (Cameroon).
     */
    #[Label('English (Cameroon)')]
    case EN_CM = 'en_CM';

    /**
     * English (Canada).
     */
    #[Label('English (Canada)')]
    case EN_CA = 'en_CA';

    /**
     * English (Cayman Islands).
     */
    #[Label('English (Cayman Islands)')]
    case EN_KY = 'en_KY';

    /**
     * English (Christmas Island).
     */
    #[Label('English (Christmas Island)')]
    case EN_CX = 'en_CX';

    /**
     * English (Cocos (Keeling) Islands).
     */
    #[Label('English (Cocos (Keeling) Islands)')]
    case EN_CC = 'en_CC';

    /**
     * English (Cook Islands).
     */
    #[Label('English (Cook Islands)')]
    case EN_CK = 'en_CK';

    /**
     * English (Diego Garcia).
     */
    #[Label('English (Diego Garcia)')]
    case EN_DG = 'en_DG';

    /**
     * English (Dominica).
     */
    #[Label('English (Dominica)')]
    case EN_DM = 'en_DM';

    /**
     * English (Eritrea).
     */
    #[Label('English (Eritrea)')]
    case EN_ER = 'en_ER';

    /**
     * English (Falkland Islands).
     */
    #[Label('English (Falkland Islands)')]
    case EN_FK = 'en_FK';

    /**
     * English (Fiji).
     */
    #[Label('English (Fiji)')]
    case EN_FJ = 'en_FJ';

    /**
     * English (Gambia).
     */
    #[Label('English (Gambia)')]
    case EN_GM = 'en_GM';

    /**
     * English (Ghana).
     */
    #[Label('English (Ghana)')]
    case EN_GH = 'en_GH';

    /**
     * English (Gibraltar).
     */
    #[Label('English (Gibraltar)')]
    case EN_GI = 'en_GI';

    /**
     * English (Grenada).
     */
    #[Label('English (Grenada)')]
    case EN_GD = 'en_GD';

    /**
     * English (Guam).
     */
    #[Label('English (Guam)')]
    case EN_GU = 'en_GU';

    /**
     * English (Guernsey).
     */
    #[Label('English (Guernsey)')]
    case EN_GG = 'en_GG';

    /**
     * English (Guyana).
     */
    #[Label('English (Guyana)')]
    case EN_GY = 'en_GY';

    /**
     * English (Hong Kong SAR China).
     */
    #[Label('English (Hong Kong SAR China)')]
    case EN_HK = 'en_HK';

    /**
     * English (India).
     */
    #[Label('English (India)')]
    case EN_IN = 'en_IN';

    /**
     * English (Ireland).
     */
    #[Label('English (Ireland)')]
    case EN_IE = 'en_IE';

    /**
     * English (Isle of Man).
     */
    #[Label('English (Isle of Man)')]
    case EN_IM = 'en_IM';

    /**
     * English (Jamaica).
     */
    #[Label('English (Jamaica)')]
    case EN_JM = 'en_JM';

    /**
     * English (Jersey).
     */
    #[Label('English (Jersey)')]
    case EN_JE = 'en_JE';

    /**
     * English (Kenya).
     */
    #[Label('English (Kenya)')]
    case EN_KE = 'en_KE';

    /**
     * English (Kiribati).
     */
    #[Label('English (Kiribati)')]
    case EN_KI = 'en_KI';

    /**
     * English (Lesotho).
     */
    #[Label('English (Lesotho)')]
    case EN_LS = 'en_LS';

    /**
     * English (Liberia).
     */
    #[Label('English (Liberia)')]
    case EN_LR = 'en_LR';

    /**
     * English (Macau SAR China).
     */
    #[Label('English (Macau SAR China)')]
    case EN_MO = 'en_MO';

    /**
     * English (Madagascar).
     */
    #[Label('English (Madagascar)')]
    case EN_MG = 'en_MG';

    /**
     * English (Malawi).
     */
    #[Label('English (Malawi)')]
    case EN_MW = 'en_MW';

    /**
     * English (Malaysia).
     */
    #[Label('English (Malaysia)')]
    case EN_MY = 'en_MY';

    /**
     * English (Malta).
     */
    #[Label('English (Malta)')]
    case EN_MT = 'en_MT';

    /**
     * English (Marshall Islands).
     */
    #[Label('English (Marshall Islands)')]
    case EN_MH = 'en_MH';

    /**
     * English (Mauritius).
     */
    #[Label('English (Mauritius)')]
    case EN_MU = 'en_MU';

    /**
     * English (Micronesia).
     */
    #[Label('English (Micronesia)')]
    case EN_FM = 'en_FM';

    /**
     * English (Montserrat).
     */
    #[Label('English (Montserrat)')]
    case EN_MS = 'en_MS';

    /**
     * English (Namibia).
     */
    #[Label('English (Namibia)')]
    case EN_NA = 'en_NA';

    /**
     * English (Nauru).
     */
    #[Label('English (Nauru)')]
    case EN_NR = 'en_NR';

    /**
     * English (New Zealand).
     */
    #[Label('English (New Zealand)')]
    case EN_NZ = 'en_NZ';

    /**
     * English (Nigeria).
     */
    #[Label('English (Nigeria)')]
    case EN_NG = 'en_NG';

    /**
     * English (Niue).
     */
    #[Label('English (Niue)')]
    case EN_NU = 'en_NU';

    /**
     * English (Norfolk Island).
     */
    #[Label('English (Norfolk Island)')]
    case EN_NF = 'en_NF';

    /**
     * English (Northern Mariana Islands).
     */
    #[Label('English (Northern Mariana Islands)')]
    case EN_MP = 'en_MP';

    /**
     * English (Pakistan).
     */
    #[Label('English (Pakistan)')]
    case EN_PK = 'en_PK';

    /**
     * English (Palau).
     */
    #[Label('English (Palau)')]
    case EN_PW = 'en_PW';

    /**
     * English (Papua New Guinea).
     */
    #[Label('English (Papua New Guinea)')]
    case EN_PG = 'en_PG';

    /**
     * English (Philippines).
     */
    #[Label('English (Philippines)')]
    case EN_PH = 'en_PH';

    /**
     * English (Pitcairn Islands).
     */
    #[Label('English (Pitcairn Islands)')]
    case EN_PN = 'en_PN';

    /**
     * English (Puerto Rico).
     */
    #[Label('English (Puerto Rico)')]
    case EN_PR = 'en_PR';

    /**
     * English (Samoa).
     */
    #[Label('English (Samoa)')]
    case EN_WS = 'en_WS';

    /**
     * English (Seychelles).
     */
    #[Label('English (Seychelles)')]
    case EN_SC = 'en_SC';

    /**
     * English (Sierra Leone).
     */
    #[Label('English (Sierra Leone)')]
    case EN_SL = 'en_SL';

    /**
     * English (Singapore).
     */
    #[Label('English (Singapore)')]
    case EN_SG = 'en_SG';

    /**
     * English (Sint Maarten).
     */
    #[Label('English (Sint Maarten)')]
    case EN_SX = 'en_SX';

    /**
     * English (Solomon Islands).
     */
    #[Label('English (Solomon Islands)')]
    case EN_SB = 'en_SB';

    /**
     * English (South Africa).
     */
    #[Label('English (South Africa)')]
    case EN_ZA = 'en_ZA';

    /**
     * English (South Sudan).
     */
    #[Label('English (South Sudan)')]
    case EN_SS = 'en_SS';

    /**
     * English (St. Helena).
     */
    #[Label('English (St. Helena)')]
    case EN_SH = 'en_SH';

    /**
     * English (St. Kitts & Nevis).
     */
    #[Label('English (St. Kitts & Nevis)')]
    case EN_KN = 'en_KN';

    /**
     * English (St. Lucia).
     */
    #[Label('English (St. Lucia)')]
    case EN_LC = 'en_LC';

    /**
     * English (St. Vincent & Grenadines).
     */
    #[Label('English (St. Vincent & Grenadines)')]
    case EN_VC = 'en_VC';

    /**
     * English (Sudan).
     */
    #[Label('English (Sudan)')]
    case EN_SD = 'en_SD';

    /**
     * English (Swaziland).
     */
    #[Label('English (Swaziland)')]
    case EN_SZ = 'en_SZ';

    /**
     * English (Tanzania).
     */
    #[Label('English (Tanzania)')]
    case EN_TZ = 'en_TZ';

    /**
     * English (Tokelau).
     */
    #[Label('English (Tokelau)')]
    case EN_TK = 'en_TK';

    /**
     * English (Trinidad & Tobago).
     */
    #[Label('English (Trinidad & Tobago)')]
    case EN_TT = 'en_TT';

    /**
     * English (Turks & Caicos Islands).
     */
    #[Label('English (Turks & Caicos Islands)')]
    case EN_TC = 'en_TC';

    /**
     * English (Tuvalu).
     */
    #[Label('English (Tuvalu)')]
    case EN_TV = 'en_TV';

    /**
     * English (U.S. Outlying Islands).
     */
    #[Label('English (U.S. Outlying Islands)')]
    case EN_UM = 'en_UM';

    /**
     * English (U.S. Virgin Islands).
     */
    #[Label('English (U.S. Virgin Islands)')]
    case EN_VI = 'en_VI';

    /**
     * English (Uganda).
     */
    #[Label('English (Uganda)')]
    case EN_UG = 'en_UG';

    /**
     * English (United Kingdom).
     */
    #[Label('English (United Kingdom)')]
    case EN_GB = 'en_GB';

    /**
     * English (United States).
     */
    #[Label('English (United States)')]
    case EN_US = 'en_US';

    /**
     * English (Vanuatu).
     */
    #[Label('English (Vanuatu)')]
    case EN_VU = 'en_VU';

    /**
     * English (Zambia).
     */
    #[Label('English (Zambia)')]
    case EN_ZM = 'en_ZM';

    /**
     * English (Zimbabwe).
     */
    #[Label('English (Zimbabwe)')]
    case EN_ZW = 'en_ZW';

    /**
     * Estonian (Estonia).
     */
    #[Label('Estonian (Estonia)')]
    case ET_EE = 'et_EE';

    /**
     * Ewe.
     */
    #[Label('Ewe')]
    case EE = 'ee';

    /**
     * Ewe (Ghana).
     */
    #[Label('Ewe (Ghana)')]
    case EE_GH = 'ee_GH';

    /**
     * Ewe (Togo).
     */
    #[Label('Ewe (Togo)')]
    case EE_TG = 'ee_TG';

    /**
     * Faroese (Faroe Islands).
     */
    #[Label('Faroese (Faroe Islands)')]
    case FO_FO = 'fo_FO';

    /**
     * Finnish (Finland).
     */
    #[Label('Finnish (Finland)')]
    case FI_FI = 'fi_FI';

    /**
     * French (Algeria).
     */
    #[Label('French (Algeria)')]
    case FR_DZ = 'fr_DZ';

    /**
     * French (Belgium).
     */
    #[Label('French (Belgium)')]
    case FR_BE = 'fr_BE';

    /**
     * French (Benin).
     */
    #[Label('French (Benin)')]
    case FR_BJ = 'fr_BJ';

    /**
     * French (Burkina Faso).
     */
    #[Label('French (Burkina Faso)')]
    case FR_BF = 'fr_BF';

    /**
     * French (Burundi).
     */
    #[Label('French (Burundi)')]
    case FR_BI = 'fr_BI';

    /**
     * French (Cameroon).
     */
    #[Label('French (Cameroon)')]
    case FR_CM = 'fr_CM';

    /**
     * French (Canada).
     */
    #[Label('French (Canada)')]
    case FR_CA = 'fr_CA';

    /**
     * French (Central African Republic).
     */
    #[Label('French (Central African Republic)')]
    case FR_CF = 'fr_CF';

    /**
     * French (Chad).
     */
    #[Label('French (Chad)')]
    case FR_TD = 'fr_TD';

    /**
     * French (Comoros).
     */
    #[Label('French (Comoros)')]
    case FR_KM = 'fr_KM';

    /**
     * French (Congo - Brazzaville).
     */
    #[Label('French (Congo - Brazzaville)')]
    case FR_CG = 'fr_CG';

    /**
     * French (Congo - Kinshasa).
     */
    #[Label('French (Congo - Kinshasa)')]
    case FR_CD = 'fr_CD';

    /**
     * French (Côte d’Ivoire).
     */
    #[Label('French (Côte d’Ivoire)')]
    case FR_CI = 'fr_CI';

    /**
     * French (Djibouti).
     */
    #[Label('French (Djibouti)')]
    case FR_DJ = 'fr_DJ';

    /**
     * French (Equatorial Guinea).
     */
    #[Label('French (Equatorial Guinea)')]
    case FR_GQ = 'fr_GQ';

    /**
     * French (France).
     */
    #[Label('French (France)')]
    case FR_FR = 'fr_FR';

    /**
     * French (French Guiana).
     */
    #[Label('French (French Guiana)')]
    case FR_GF = 'fr_GF';

    /**
     * French (French Polynesia).
     */
    #[Label('French (French Polynesia)')]
    case FR_PF = 'fr_PF';

    /**
     * French (Gabon).
     */
    #[Label('French (Gabon)')]
    case FR_GA = 'fr_GA';

    /**
     * French (Guadeloupe).
     */
    #[Label('French (Guadeloupe)')]
    case FR_GP = 'fr_GP';

    /**
     * French (Guinea).
     */
    #[Label('French (Guinea)')]
    case FR_GN = 'fr_GN';

    /**
     * French (Haiti).
     */
    #[Label('French (Haiti)')]
    case FR_HT = 'fr_HT';

    /**
     * French (Luxembourg).
     */
    #[Label('French (Luxembourg)')]
    case FR_LU = 'fr_LU';

    /**
     * French (Madagascar).
     */
    #[Label('French (Madagascar)')]
    case FR_MG = 'fr_MG';

    /**
     * French (Mali).
     */
    #[Label('French (Mali)')]
    case FR_ML = 'fr_ML';

    /**
     * French (Martinique).
     */
    #[Label('French (Martinique)')]
    case FR_MQ = 'fr_MQ';

    /**
     * French (Mauritania).
     */
    #[Label('French (Mauritania)')]
    case FR_MR = 'fr_MR';

    /**
     * French (Mauritius).
     */
    #[Label('French (Mauritius)')]
    case FR_MU = 'fr_MU';

    /**
     * French (Mayotte).
     */
    #[Label('French (Mayotte)')]
    case FR_YT = 'fr_YT';

    /**
     * French (Monaco).
     */
    #[Label('French (Monaco)')]
    case FR_MC = 'fr_MC';

    /**
     * French (Morocco).
     */
    #[Label('French (Morocco)')]
    case FR_MA = 'fr_MA';

    /**
     * French (New Caledonia).
     */
    #[Label('French (New Caledonia)')]
    case FR_NC = 'fr_NC';

    /**
     * French (Niger).
     */
    #[Label('French (Niger)')]
    case FR_NE = 'fr_NE';

    /**
     * French (Réunion).
     */
    #[Label('French (Réunion)')]
    case FR_RE = 'fr_RE';

    /**
     * French (Senegal).
     */
    #[Label('French (Senegal)')]
    case FR_SN = 'fr_SN';

    /**
     * French (Seychelles).
     */
    #[Label('French (Seychelles)')]
    case FR_SC = 'fr_SC';

    /**
     * French (St. Barthélemy).
     */
    #[Label('French (St. Barthélemy)')]
    case FR_BL = 'fr_BL';

    /**
     * French (St. Martin).
     */
    #[Label('French (St. Martin)')]
    case FR_MF = 'fr_MF';

    /**
     * French (St. Pierre & Miquelon).
     */
    #[Label('French (St. Pierre & Miquelon)')]
    case FR_PM = 'fr_PM';

    /**
     * French (Switzerland).
     */
    #[Label('French (Switzerland)')]
    case FR_CH = 'fr_CH';

    /**
     * French (Syria).
     */
    #[Label('French (Syria)')]
    case FR_SY = 'fr_SY';

    /**
     * French (Togo).
     */
    #[Label('French (Togo)')]
    case FR_TG = 'fr_TG';

    /**
     * French (Tunisia).
     */
    #[Label('French (Tunisia)')]
    case FR_TN = 'fr_TN';

    /**
     * French (Vanuatu).
     */
    #[Label('French (Vanuatu)')]
    case FR_VU = 'fr_VU';

    /**
     * French (Wallis & Futuna).
     */
    #[Label('French (Wallis & Futuna)')]
    case FR_WF = 'fr_WF';

    /**
     * Fulah.
     */
    #[Label('Fulah')]
    case FF = 'ff';

    /**
     * Fulah (Cameroon).
     */
    #[Label('Fulah (Cameroon)')]
    case FF_CM = 'ff_CM';

    /**
     * Fulah (Guinea).
     */
    #[Label('Fulah (Guinea)')]
    case FF_GN = 'ff_GN';

    /**
     * Fulah (Mauritania).
     */
    #[Label('Fulah (Mauritania)')]
    case FF_MR = 'ff_MR';

    /**
     * Fulah (Senegal).
     */
    #[Label('Fulah (Senegal)')]
    case FF_SN = 'ff_SN';

    /**
     * Galician (Spain).
     */
    #[Label('Galician (Spain)')]
    case GL_ES = 'gl_ES';

    /**
     * Ganda.
     */
    #[Label('Ganda')]
    case LG = 'lg';

    /**
     * Ganda (Uganda).
     */
    #[Label('Ganda (Uganda)')]
    case LG_UG = 'lg_UG';

    /**
     * Georgian (Georgia).
     */
    #[Label('Georgian (Georgia)')]
    case KA_GE = 'ka_GE';

    /**
     * German (Austria).
     */
    #[Label('German (Austria)')]
    case DE_AT = 'de_AT';

    /**
     * German (Belgium).
     */
    #[Label('German (Belgium)')]
    case DE_BE = 'de_BE';

    /**
     * German (Germany).
     */
    #[Label('German (Germany)')]
    case DE_DE = 'de_DE';

    /**
     * German (Liechtenstein).
     */
    #[Label('German (Liechtenstein)')]
    case DE_LI = 'de_LI';

    /**
     * German (Luxembourg).
     */
    #[Label('German (Luxembourg)')]
    case DE_LU = 'de_LU';

    /**
     * German (Switzerland).
     */
    #[Label('German (Switzerland)')]
    case DE_CH = 'de_CH';

    /**
     * Greek (Cyprus).
     */
    #[Label('Greek (Cyprus)')]
    case EL_CY = 'el_CY';

    /**
     * Greek (Greece).
     */
    #[Label('Greek (Greece)')]
    case EL_GR = 'el_GR';

    /**
     * Gujarati (India).
     */
    #[Label('Gujarati (India)')]
    case GU_IN = 'gu_IN';

    /**
     * Hausa.
     */
    #[Label('Hausa')]
    case HA = 'ha';

    /**
     * Hausa (Ghana).
     */
    #[Label('Hausa (Ghana)')]
    case HA_GH = 'ha_GH';

    /**
     * Hausa (Latin, Ghana).
     */
    #[Label('Hausa (Latin, Ghana)')]
    case HA_LATN_GH = 'ha_Latn_GH';

    /**
     * Hausa (Latin, Niger).
     */
    #[Label('Hausa (Latin, Niger)')]
    case HA_LATN_NE = 'ha_Latn_NE';

    /**
     * Hausa (Latin, Nigeria).
     */
    #[Label('Hausa (Latin, Nigeria)')]
    case HA_LATN_NG = 'ha_Latn_NG';

    /**
     * Hausa (Latin).
     */
    #[Label('Hausa (Latin)')]
    case HA_LATN = 'ha_Latn';

    /**
     * Hausa (Niger).
     */
    #[Label('Hausa (Niger)')]
    case HA_NE = 'ha_NE';

    /**
     * Hausa (Nigeria).
     */
    #[Label('Hausa (Nigeria)')]
    case HA_NG = 'ha_NG';

    /**
     * Hebrew (Israel).
     */
    #[Label('Hebrew (Israel)')]
    case HE_IL = 'he_IL';

    /**
     * Hindi (India).
     */
    #[Label('Hindi (India)')]
    case HI_IN = 'hi_IN';

    /**
     * Hungarian (Hungary).
     */
    #[Label('Hungarian (Hungary)')]
    case HU_HU = 'hu_HU';

    /**
     * Icelandic (Iceland).
     */
    #[Label('Icelandic (Iceland)')]
    case IS_IS = 'is_IS';

    /**
     * Icyalubaniya.
     */
    #[Label('Icyalubaniya')]
    case SQ = 'sq';

    /**
     * Icyarabu.
     */
    #[Label('Icyarabu')]
    case AR = 'ar';

    /**
     * Icyasamizi.
     */
    #[Label('Icyasamizi')]
    case AS = 'as';

    /**
     * Icyesipanyolo.
     */
    #[Label('Icyesipanyolo')]
    case ES = 'es';

    /**
     * Icyesiperanto.
     */
    #[Label('Icyesiperanto')]
    case EO = 'eo';

    /**
     * Icyesitoniya.
     */
    #[Label('Icyesitoniya')]
    case ET = 'et';

    /**
     * Icyongereza.
     */
    #[Label('Icyongereza')]
    case EN = 'en';

    /**
     * Icyongereza (Igitonga).
     */
    #[Label('Icyongereza (Igitonga)')]
    case EN_TO = 'en_TO';

    /**
     * Icyongereza (Rwanda).
     */
    #[Label('Icyongereza (Rwanda)')]
    case EN_RW = 'en_RW';

    /**
     * Igbo.
     */
    #[Label('Igbo')]
    case IG = 'ig';

    /**
     * Igbo (Nigeria).
     */
    #[Label('Igbo (Nigeria)')]
    case IG_NG = 'ig_NG';

    /**
     * Igiceke.
     */
    #[Label('Igiceke')]
    case CS = 'cs';

    /**
     * Igifaransa.
     */
    #[Label('Igifaransa')]
    case FR = 'fr';

    /**
     * Igifaransa (Rwanda).
     */
    #[Label('Igifaransa (Rwanda)')]
    case FR_RW = 'fr_RW';

    /**
     * Igifinilande.
     */
    #[Label('Igifinilande')]
    case FI = 'fi';

    /**
     * Igifiriziyani.
     */
    #[Label('Igifiriziyani')]
    case FY = 'fy';

    /**
     * Igiheburayo.
     */
    #[Label('Igiheburayo')]
    case HE = 'he';

    /**
     * Igihindi.
     */
    #[Label('Igihindi')]
    case HI = 'hi';

    /**
     * Igihongiriya.
     */
    #[Label('Igihongiriya')]
    case HU = 'hu';

    /**
     * Igikambodiya.
     */
    #[Label('Igikambodiya')]
    case KM = 'km';

    /**
     * Igikanada.
     */
    #[Label('Igikanada')]
    case KN = 'kn';

    /**
     * Igikatalani.
     */
    #[Label('Igikatalani')]
    case CA = 'ca';

    /**
     * Igikoreya.
     */
    #[Label('Igikoreya')]
    case KO = 'ko';

    /**
     * Igikorowasiya.
     */
    #[Label('Igikorowasiya')]
    case HR = 'hr';

    /**
     * Igipolone.
     */
    #[Label('Igipolone')]
    case PL = 'pl';

    /**
     * Igiporutugali.
     */
    #[Label('Igiporutugali')]
    case PT = 'pt';

    /**
     * Igipunjabi.
     */
    #[Label('Igipunjabi')]
    case PA = 'pa';

    /**
     * Igiseribe.
     */
    #[Label('Igiseribe')]
    case SR = 'sr';

    /**
     * Igisilande.
     */
    #[Label('Igisilande')]
    case IS = 'is';

    /**
     * Igisilovaki.
     */
    #[Label('Igisilovaki')]
    case SK = 'sk';

    /**
     * Igisomali.
     */
    #[Label('Igisomali')]
    case SO = 'so';

    /**
     * Igisuweduwa.
     */
    #[Label('Igisuweduwa')]
    case SV = 'sv';

    /**
     * Igiswahili.
     */
    #[Label('Igiswahili')]
    case SW = 'sw';

    /**
     * Igitaliyani.
     */
    #[Label('Igitaliyani')]
    case IT = 'it';

    /**
     * Igitamili.
     */
    #[Label('Igitamili')]
    case TA = 'ta';

    /**
     * Igitayi.
     */
    #[Label('Igitayi')]
    case TH = 'th';

    /**
     * Igitelugu.
     */
    #[Label('Igitelugu')]
    case TE = 'te';

    /**
     * Igiturukiya.
     */
    #[Label('Igiturukiya')]
    case TR = 'tr';

    /**
     * Ikibasiki.
     */
    #[Label('Ikibasiki')]
    case EU = 'eu';

    /**
     * Ikibelarusiya.
     */
    #[Label('Ikibelarusiya')]
    case BE = 'be';

    /**
     * Ikibengali.
     */
    #[Label('Ikibengali')]
    case BN = 'bn';

    /**
     * Ikidage.
     */
    #[Label('Ikidage')]
    case DE = 'de';

    /**
     * Ikidaninwa.
     */
    #[Label('Ikidaninwa')]
    case DA = 'da';

    /**
     * Ikigalisiya.
     */
    #[Label('Ikigalisiya')]
    case GL = 'gl';

    /**
     * Ikigaluwa.
     */
    #[Label('Ikigaluwa')]
    case CY = 'cy';

    /**
     * Ikigaluwa cy’Igisweduwa.
     */
    #[Label('Ikigaluwa cy’Igisweduwa')]
    case GD = 'gd';

    /**
     * Ikigereki.
     */
    #[Label('Ikigereki')]
    case EL = 'el';

    /**
     * Ikilawotiyani.
     */
    #[Label('Ikilawotiyani')]
    case LO = 'lo';

    /**
     * Ikilituwaniya.
     */
    #[Label('Ikilituwaniya')]
    case LT = 'lt';

    /**
     * Ikimalayalami.
     */
    #[Label('Ikimalayalami')]
    case ML = 'ml';

    /**
     * Ikimalayi.
     */
    #[Label('Ikimalayi')]
    case MS = 'ms';

    /**
     * Ikimaliteze.
     */
    #[Label('Ikimaliteze')]
    case MT = 'mt';

    /**
     * Ikimarati.
     */
    #[Label('Ikimarati')]
    case MR = 'mr';

    /**
     * Ikimasedoniyani.
     */
    #[Label('Ikimasedoniyani')]
    case MK = 'mk';

    /**
     * Ikimongoli.
     */
    #[Label('Ikimongoli')]
    case MN = 'mn';

    /**
     * Ikinepali.
     */
    #[Label('Ikinepali')]
    case NE = 'ne';

    /**
     * Ikinerilande.
     */
    #[Label('Ikinerilande')]
    case NL = 'nl';

    /**
     * Ikinoruveji.
     */
    #[Label('Ikinoruveji')]
    case NO = 'no';

    /**
     * Ikinyafurikaneri.
     */
    #[Label('Ikinyafurikaneri')]
    case AF = 'af';

    /**
     * Ikinyaletoviyani.
     */
    #[Label('Ikinyaletoviyani')]
    case LV = 'lv';

    /**
     * Ikinyarumaniya.
     */
    #[Label('Ikinyarumaniya')]
    case RO = 'ro';

    /**
     * Ikinyarumeniya.
     */
    #[Label('Ikinyarumeniya')]
    case HY = 'hy';

    /**
     * Ikinyasiloveniya.
     */
    #[Label('Ikinyasiloveniya')]
    case SL = 'sl';

    /**
     * Ikinyaviyetinamu.
     */
    #[Label('Ikinyaviyetinamu')]
    case VI = 'vi';

    /**
     * Ikinyayukereni.
     */
    #[Label('Ikinyayukereni')]
    case UK = 'uk';

    /**
     * Ikinyendoziya.
     */
    #[Label('Ikinyendoziya')]
    case ID = 'id';

    /**
     * Ikirilandi.
     */
    #[Label('Ikirilandi')]
    case GA = 'ga';

    /**
     * Ikirusiya.
     */
    #[Label('Ikirusiya')]
    case RU = 'ru';

    /**
     * Ikiwiguri.
     */
    #[Label('Ikiwiguri')]
    case UG = 'ug';

    /**
     * Ikiyapani.
     */
    #[Label('Ikiyapani')]
    case JA = 'ja';

    /**
     * Ilingala.
     */
    #[Label('Ilingala')]
    case LN = 'ln';

    /**
     * Impashito.
     */
    #[Label('Impashito')]
    case PS = 'ps';

    /**
     * Indonesian (Indonesia).
     */
    #[Label('Indonesian (Indonesia)')]
    case ID_ID = 'id_ID';

    /**
     * Inkerigizi.
     */
    #[Label('Inkerigizi')]
    case KY = 'ky';

    /**
     * Inyamuhariki.
     */
    #[Label('Inyamuhariki')]
    case AM = 'am';

    /**
     * Inyatigirinya.
     */
    #[Label('Inyatigirinya')]
    case TI = 'ti';

    /**
     * Inyazeribayijani.
     */
    #[Label('Inyazeribayijani')]
    case AZ = 'az';

    /**
     * Inyebiritoni.
     */
    #[Label('Inyebiritoni')]
    case BR = 'br';

    /**
     * Inyebosiniya.
     */
    #[Label('Inyebosiniya')]
    case BS = 'bs';

    /**
     * Inyefaroyizi.
     */
    #[Label('Inyefaroyizi')]
    case FO = 'fo';

    /**
     * Inyegujarati.
     */
    #[Label('Inyegujarati')]
    case GU = 'gu';

    /**
     * Inyejeworujiya.
     */
    #[Label('Inyejeworujiya')]
    case KA = 'ka';

    /**
     * Inyenoruveji (Nyonorusiki).
     */
    #[Label('Inyenoruveji (Nyonorusiki)')]
    case NN = 'nn';

    /**
     * Inyeperisi.
     */
    #[Label('Inyeperisi')]
    case FA = 'fa';

    /**
     * Inyeseribiya na Korowasiya.
     */
    #[Label('Inyeseribiya na Korowasiya')]
    case SH = 'sh';

    /**
     * Inyesimpaleze.
     */
    #[Label('Inyesimpaleze')]
    case SI = 'si';

    /**
     * Inyeyidishi.
     */
    #[Label('Inyeyidishi')]
    case YI = 'yi';

    /**
     * Inyeyurudu.
     */
    #[Label('Inyeyurudu')]
    case UR = 'ur';

    /**
     * Inyeyuzubeki.
     */
    #[Label('Inyeyuzubeki')]
    case UZ = 'uz';

    /**
     * Inyezulu.
     */
    #[Label('Inyezulu')]
    case ZU = 'zu';

    /**
     * Inyoriya.
     */
    #[Label('Inyoriya')]
    case OR = 'or';

    /**
     * Irish (Ireland).
     */
    #[Label('Irish (Ireland)')]
    case GA_IE = 'ga_IE';

    /**
     * Italian (Italy).
     */
    #[Label('Italian (Italy)')]
    case IT_IT = 'it_IT';

    /**
     * Italian (San Marino).
     */
    #[Label('Italian (San Marino)')]
    case IT_SM = 'it_SM';

    /**
     * Italian (Switzerland).
     */
    #[Label('Italian (Switzerland)')]
    case IT_CH = 'it_CH';

    /**
     * Japanese (Japan).
     */
    #[Label('Japanese (Japan)')]
    case JA_JP = 'ja_JP';

    /**
     * Kalaallisut.
     */
    #[Label('Kalaallisut')]
    case KL = 'kl';

    /**
     * Kalaallisut (Greenland).
     */
    #[Label('Kalaallisut (Greenland)')]
    case KL_GL = 'kl_GL';

    /**
     * Kannada (India).
     */
    #[Label('Kannada (India)')]
    case KN_IN = 'kn_IN';

    /**
     * Kashmiri.
     */
    #[Label('Kashmiri')]
    case KS = 'ks';

    /**
     * Kashmiri (Arabic, India).
     */
    #[Label('Kashmiri (Arabic, India)')]
    case KS_ARAB_IN = 'ks_Arab_IN';

    /**
     * Kashmiri (Arabic).
     */
    #[Label('Kashmiri (Arabic)')]
    case KS_ARAB = 'ks_Arab';

    /**
     * Kashmiri (India).
     */
    #[Label('Kashmiri (India)')]
    case KS_IN = 'ks_IN';

    /**
     * Kazakh.
     */
    #[Label('Kazakh')]
    case KK = 'kk';

    /**
     * Kazakh (Cyrillic, Kazakhstan).
     */
    #[Label('Kazakh (Cyrillic, Kazakhstan)')]
    case KK_CYRL_KZ = 'kk_Cyrl_KZ';

    /**
     * Kazakh (Cyrillic).
     */
    #[Label('Kazakh (Cyrillic)')]
    case KK_CYRL = 'kk_Cyrl';

    /**
     * Kazakh (Kazakhstan).
     */
    #[Label('Kazakh (Kazakhstan)')]
    case KK_KZ = 'kk_KZ';

    /**
     * Khmer (Cambodia).
     */
    #[Label('Khmer (Cambodia)')]
    case KM_KH = 'km_KH';

    /**
     * Kikuyu.
     */
    #[Label('Kikuyu')]
    case KI = 'ki';

    /**
     * Kikuyu (Kenya).
     */
    #[Label('Kikuyu (Kenya)')]
    case KI_KE = 'ki_KE';

    /**
     * Kinyarwanda.
     */
    #[Label('Kinyarwanda')]
    case RW = 'rw';

    /**
     * Kinyarwanda (Rwanda).
     */
    #[Label('Kinyarwanda (Rwanda)')]
    case RW_RW = 'rw_RW';

    /**
     * Korean (North Korea).
     */
    #[Label('Korean (North Korea)')]
    case KO_KP = 'ko_KP';

    /**
     * Korean (South Korea).
     */
    #[Label('Korean (South Korea)')]
    case KO_KR = 'ko_KR';

    /**
     * Kyrgyz (Cyrillic, Kyrgyzstan).
     */
    #[Label('Kyrgyz (Cyrillic, Kyrgyzstan)')]
    case KY_CYRL_KG = 'ky_Cyrl_KG';

    /**
     * Kyrgyz (Cyrillic).
     */
    #[Label('Kyrgyz (Cyrillic)')]
    case KY_CYRL = 'ky_Cyrl';

    /**
     * Kyrgyz (Kyrgyzstan).
     */
    #[Label('Kyrgyz (Kyrgyzstan)')]
    case KY_KG = 'ky_KG';

    /**
     * Lao (Laos).
     */
    #[Label('Lao (Laos)')]
    case LO_LA = 'lo_LA';

    /**
     * Latvian (Latvia).
     */
    #[Label('Latvian (Latvia)')]
    case LV_LV = 'lv_LV';

    /**
     * Lingala (Angola).
     */
    #[Label('Lingala (Angola)')]
    case LN_AO = 'ln_AO';

    /**
     * Lingala (Central African Republic).
     */
    #[Label('Lingala (Central African Republic)')]
    case LN_CF = 'ln_CF';

    /**
     * Lingala (Congo - Brazzaville).
     */
    #[Label('Lingala (Congo - Brazzaville)')]
    case LN_CG = 'ln_CG';

    /**
     * Lingala (Congo - Kinshasa).
     */
    #[Label('Lingala (Congo - Kinshasa)')]
    case LN_CD = 'ln_CD';

    /**
     * Lithuanian (Lithuania).
     */
    #[Label('Lithuanian (Lithuania)')]
    case LT_LT = 'lt_LT';

    /**
     * Luba-Katanga.
     */
    #[Label('Luba-Katanga')]
    case LU = 'lu';

    /**
     * Luba-Katanga (Congo - Kinshasa).
     */
    #[Label('Luba-Katanga (Congo - Kinshasa)')]
    case LU_CD = 'lu_CD';

    /**
     * Luxembourgish.
     */
    #[Label('Luxembourgish')]
    case LB = 'lb';

    /**
     * Luxembourgish (Luxembourg).
     */
    #[Label('Luxembourgish (Luxembourg)')]
    case LB_LU = 'lb_LU';

    /**
     * Macedonian (Macedonia).
     */
    #[Label('Macedonian (Macedonia)')]
    case MK_MK = 'mk_MK';

    /**
     * Malagasy.
     */
    #[Label('Malagasy')]
    case MG = 'mg';

    /**
     * Malagasy (Madagascar).
     */
    #[Label('Malagasy (Madagascar)')]
    case MG_MG = 'mg_MG';

    /**
     * Malay (Brunei).
     */
    #[Label('Malay (Brunei)')]
    case MS_BN = 'ms_BN';

    /**
     * Malay (Latin, Brunei).
     */
    #[Label('Malay (Latin, Brunei)')]
    case MS_LATN_BN = 'ms_Latn_BN';

    /**
     * Malay (Latin, Malaysia).
     */
    #[Label('Malay (Latin, Malaysia)')]
    case MS_LATN_MY = 'ms_Latn_MY';

    /**
     * Malay (Latin, Singapore).
     */
    #[Label('Malay (Latin, Singapore)')]
    case MS_LATN_SG = 'ms_Latn_SG';

    /**
     * Malay (Latin).
     */
    #[Label('Malay (Latin)')]
    case MS_LATN = 'ms_Latn';

    /**
     * Malay (Malaysia).
     */
    #[Label('Malay (Malaysia)')]
    case MS_MY = 'ms_MY';

    /**
     * Malay (Singapore).
     */
    #[Label('Malay (Singapore)')]
    case MS_SG = 'ms_SG';

    /**
     * Malayalam (India).
     */
    #[Label('Malayalam (India)')]
    case ML_IN = 'ml_IN';

    /**
     * Maltese (Malta).
     */
    #[Label('Maltese (Malta)')]
    case MT_MT = 'mt_MT';

    /**
     * Manx.
     */
    #[Label('Manx')]
    case GV = 'gv';

    /**
     * Manx (Isle of Man).
     */
    #[Label('Manx (Isle of Man)')]
    case GV_IM = 'gv_IM';

    /**
     * Marathi (India).
     */
    #[Label('Marathi (India)')]
    case MR_IN = 'mr_IN';

    /**
     * Mongolian (Cyrillic, Mongolia).
     */
    #[Label('Mongolian (Cyrillic, Mongolia)')]
    case MN_CYRL_MN = 'mn_Cyrl_MN';

    /**
     * Mongolian (Cyrillic).
     */
    #[Label('Mongolian (Cyrillic)')]
    case MN_CYRL = 'mn_Cyrl';

    /**
     * Mongolian (Mongolia).
     */
    #[Label('Mongolian (Mongolia)')]
    case MN_MN = 'mn_MN';

    /**
     * Nepali (India).
     */
    #[Label('Nepali (India)')]
    case NE_IN = 'ne_IN';

    /**
     * Nepali (Nepal).
     */
    #[Label('Nepali (Nepal)')]
    case NE_NP = 'ne_NP';

    /**
     * North Ndebele.
     */
    #[Label('North Ndebele')]
    case ND = 'nd';

    /**
     * North Ndebele (Zimbabwe).
     */
    #[Label('North Ndebele (Zimbabwe)')]
    case ND_ZW = 'nd_ZW';

    /**
     * Northern Sami.
     */
    #[Label('Northern Sami')]
    case SE = 'se';

    /**
     * Northern Sami (Finland).
     */
    #[Label('Northern Sami (Finland)')]
    case SE_FI = 'se_FI';

    /**
     * Northern Sami (Norway).
     */
    #[Label('Northern Sami (Norway)')]
    case SE_NO = 'se_NO';

    /**
     * Northern Sami (Sweden).
     */
    #[Label('Northern Sami (Sweden)')]
    case SE_SE = 'se_SE';

    /**
     * Norwegian (Norway).
     */
    #[Label('Norwegian (Norway)')]
    case NO_NO = 'no_NO';

    /**
     * Norwegian Bokmål.
     */
    #[Label('Norwegian Bokmål')]
    case NB = 'nb';

    /**
     * Norwegian Bokmål (Norway).
     */
    #[Label('Norwegian Bokmål (Norway)')]
    case NB_NO = 'nb_NO';

    /**
     * Norwegian Bokmål (Svalbard & Jan Mayen).
     */
    #[Label('Norwegian Bokmål (Svalbard & Jan Mayen)')]
    case NB_SJ = 'nb_SJ';

    /**
     * Norwegian Nynorsk (Norway).
     */
    #[Label('Norwegian Nynorsk (Norway)')]
    case NN_NO = 'nn_NO';

    /**
     * Oriya (India).
     */
    #[Label('Oriya (India)')]
    case OR_IN = 'or_IN';

    /**
     * Oromo.
     */
    #[Label('Oromo')]
    case OM = 'om';

    /**
     * Oromo (Ethiopia).
     */
    #[Label('Oromo (Ethiopia)')]
    case OM_ET = 'om_ET';

    /**
     * Oromo (Kenya).
     */
    #[Label('Oromo (Kenya)')]
    case OM_KE = 'om_KE';

    /**
     * Ossetic.
     */
    #[Label('Ossetic')]
    case OS = 'os';

    /**
     * Ossetic (Georgia).
     */
    #[Label('Ossetic (Georgia)')]
    case OS_GE = 'os_GE';

    /**
     * Ossetic (Russia).
     */
    #[Label('Ossetic (Russia)')]
    case OS_RU = 'os_RU';

    /**
     * Pashto (Afghanistan).
     */
    #[Label('Pashto (Afghanistan)')]
    case PS_AF = 'ps_AF';

    /**
     * Persian (Afghanistan).
     */
    #[Label('Persian (Afghanistan)')]
    case FA_AF = 'fa_AF';

    /**
     * Persian (Iran).
     */
    #[Label('Persian (Iran)')]
    case FA_IR = 'fa_IR';

    /**
     * Polish (Poland).
     */
    #[Label('Polish (Poland)')]
    case PL_PL = 'pl_PL';

    /**
     * Portuguese (Angola).
     */
    #[Label('Portuguese (Angola)')]
    case PT_AO = 'pt_AO';

    /**
     * Portuguese (Brazil).
     */
    #[Label('Portuguese (Brazil)')]
    case PT_BR = 'pt_BR';

    /**
     * Portuguese (Cape Verde).
     */
    #[Label('Portuguese (Cape Verde)')]
    case PT_CV = 'pt_CV';

    /**
     * Portuguese (Guinea-Bissau).
     */
    #[Label('Portuguese (Guinea-Bissau)')]
    case PT_GW = 'pt_GW';

    /**
     * Portuguese (Macau SAR China).
     */
    #[Label('Portuguese (Macau SAR China)')]
    case PT_MO = 'pt_MO';

    /**
     * Portuguese (Mozambique).
     */
    #[Label('Portuguese (Mozambique)')]
    case PT_MZ = 'pt_MZ';

    /**
     * Portuguese (Portugal).
     */
    #[Label('Portuguese (Portugal)')]
    case PT_PT = 'pt_PT';

    /**
     * Portuguese (São Tomé & Príncipe).
     */
    #[Label('Portuguese (São Tomé & Príncipe)')]
    case PT_ST = 'pt_ST';

    /**
     * Portuguese (Timor-Leste).
     */
    #[Label('Portuguese (Timor-Leste)')]
    case PT_TL = 'pt_TL';

    /**
     * Punjabi (Arabic, Pakistan).
     */
    #[Label('Punjabi (Arabic, Pakistan)')]
    case PA_ARAB_PK = 'pa_Arab_PK';

    /**
     * Punjabi (Arabic).
     */
    #[Label('Punjabi (Arabic)')]
    case PA_ARAB = 'pa_Arab';

    /**
     * Punjabi (Gurmukhi, India).
     */
    #[Label('Punjabi (Gurmukhi, India)')]
    case PA_GURU_IN = 'pa_Guru_IN';

    /**
     * Punjabi (Gurmukhi).
     */
    #[Label('Punjabi (Gurmukhi)')]
    case PA_GURU = 'pa_Guru';

    /**
     * Punjabi (India).
     */
    #[Label('Punjabi (India)')]
    case PA_IN = 'pa_IN';

    /**
     * Punjabi (Pakistan).
     */
    #[Label('Punjabi (Pakistan)')]
    case PA_PK = 'pa_PK';

    /**
     * Quechua.
     */
    #[Label('Quechua')]
    case QU = 'qu';

    /**
     * Quechua (Bolivia).
     */
    #[Label('Quechua (Bolivia)')]
    case QU_BO = 'qu_BO';

    /**
     * Quechua (Ecuador).
     */
    #[Label('Quechua (Ecuador)')]
    case QU_EC = 'qu_EC';

    /**
     * Quechua (Peru).
     */
    #[Label('Quechua (Peru)')]
    case QU_PE = 'qu_PE';

    /**
     * Romanian (Moldova).
     */
    #[Label('Romanian (Moldova)')]
    case RO_MD = 'ro_MD';

    /**
     * Romanian (Romania).
     */
    #[Label('Romanian (Romania)')]
    case RO_RO = 'ro_RO';

    /**
     * Romansh.
     */
    #[Label('Romansh')]
    case RM = 'rm';

    /**
     * Romansh (Switzerland).
     */
    #[Label('Romansh (Switzerland)')]
    case RM_CH = 'rm_CH';

    /**
     * Rundi.
     */
    #[Label('Rundi')]
    case RN = 'rn';

    /**
     * Rundi (Burundi).
     */
    #[Label('Rundi (Burundi)')]
    case RN_BI = 'rn_BI';

    /**
     * Russian (Belarus).
     */
    #[Label('Russian (Belarus)')]
    case RU_BY = 'ru_BY';

    /**
     * Russian (Kazakhstan).
     */
    #[Label('Russian (Kazakhstan)')]
    case RU_KZ = 'ru_KZ';

    /**
     * Russian (Kyrgyzstan).
     */
    #[Label('Russian (Kyrgyzstan)')]
    case RU_KG = 'ru_KG';

    /**
     * Russian (Moldova).
     */
    #[Label('Russian (Moldova)')]
    case RU_MD = 'ru_MD';

    /**
     * Russian (Russia).
     */
    #[Label('Russian (Russia)')]
    case RU_RU = 'ru_RU';

    /**
     * Russian (Ukraine).
     */
    #[Label('Russian (Ukraine)')]
    case RU_UA = 'ru_UA';

    /**
     * Sango.
     */
    #[Label('Sango')]
    case SG = 'sg';

    /**
     * Sango (Central African Republic).
     */
    #[Label('Sango (Central African Republic)')]
    case SG_CF = 'sg_CF';

    /**
     * Scottish Gaelic (United Kingdom).
     */
    #[Label('Scottish Gaelic (United Kingdom)')]
    case GD_GB = 'gd_GB';

    /**
     * Serbian (Bosnia & Herzegovina).
     */
    #[Label('Serbian (Bosnia & Herzegovina)')]
    case SR_BA = 'sr_BA';

    /**
     * Serbian (Cyrillic, Bosnia & Herzegovina).
     */
    #[Label('Serbian (Cyrillic, Bosnia & Herzegovina)')]
    case SR_CYRL_BA = 'sr_Cyrl_BA';

    /**
     * Serbian (Cyrillic, Kosovo).
     */
    #[Label('Serbian (Cyrillic, Kosovo)')]
    case SR_CYRL_XK = 'sr_Cyrl_XK';

    /**
     * Serbian (Cyrillic, Montenegro).
     */
    #[Label('Serbian (Cyrillic, Montenegro)')]
    case SR_CYRL_ME = 'sr_Cyrl_ME';

    /**
     * Serbian (Cyrillic, Serbia).
     */
    #[Label('Serbian (Cyrillic, Serbia)')]
    case SR_CYRL_RS = 'sr_Cyrl_RS';

    /**
     * Serbian (Cyrillic).
     */
    #[Label('Serbian (Cyrillic)')]
    case SR_CYRL = 'sr_Cyrl';

    /**
     * Serbian (Kosovo).
     */
    #[Label('Serbian (Kosovo)')]
    case SR_XK = 'sr_XK';

    /**
     * Serbian (Latin, Bosnia & Herzegovina).
     */
    #[Label('Serbian (Latin, Bosnia & Herzegovina)')]
    case SR_LATN_BA = 'sr_Latn_BA';

    /**
     * Serbian (Latin, Kosovo).
     */
    #[Label('Serbian (Latin, Kosovo)')]
    case SR_LATN_XK = 'sr_Latn_XK';

    /**
     * Serbian (Latin, Montenegro).
     */
    #[Label('Serbian (Latin, Montenegro)')]
    case SR_LATN_ME = 'sr_Latn_ME';

    /**
     * Serbian (Latin, Serbia).
     */
    #[Label('Serbian (Latin, Serbia)')]
    case SR_LATN_RS = 'sr_Latn_RS';

    /**
     * Serbian (Latin).
     */
    #[Label('Serbian (Latin)')]
    case SR_LATN = 'sr_Latn';

    /**
     * Serbian (Montenegro).
     */
    #[Label('Serbian (Montenegro)')]
    case SR_ME = 'sr_ME';

    /**
     * Serbian (Serbia).
     */
    #[Label('Serbian (Serbia)')]
    case SR_RS = 'sr_RS';

    /**
     * Serbo-Croatian (Bosnia & Herzegovina).
     */
    #[Label('Serbo-Croatian (Bosnia & Herzegovina)')]
    case SH_BA = 'sh_BA';

    /**
     * Shona.
     */
    #[Label('Shona')]
    case SN = 'sn';

    /**
     * Shona (Zimbabwe).
     */
    #[Label('Shona (Zimbabwe)')]
    case SN_ZW = 'sn_ZW';

    /**
     * Sichuan Yi.
     */
    #[Label('Sichuan Yi')]
    case II = 'ii';

    /**
     * Sichuan Yi (China).
     */
    #[Label('Sichuan Yi (China)')]
    case II_CN = 'ii_CN';

    /**
     * Sinhala (Sri Lanka).
     */
    #[Label('Sinhala (Sri Lanka)')]
    case SI_LK = 'si_LK';

    /**
     * Slovak (Slovakia).
     */
    #[Label('Slovak (Slovakia)')]
    case SK_SK = 'sk_SK';

    /**
     * Slovenian (Slovenia).
     */
    #[Label('Slovenian (Slovenia)')]
    case SL_SI = 'sl_SI';

    /**
     * Somali (Djibouti).
     */
    #[Label('Somali (Djibouti)')]
    case SO_DJ = 'so_DJ';

    /**
     * Somali (Ethiopia).
     */
    #[Label('Somali (Ethiopia)')]
    case SO_ET = 'so_ET';

    /**
     * Somali (Kenya).
     */
    #[Label('Somali (Kenya)')]
    case SO_KE = 'so_KE';

    /**
     * Somali (Somalia).
     */
    #[Label('Somali (Somalia)')]
    case SO_SO = 'so_SO';

    /**
     * Spanish (Argentina).
     */
    #[Label('Spanish (Argentina)')]
    case ES_AR = 'es_AR';

    /**
     * Spanish (Bolivia).
     */
    #[Label('Spanish (Bolivia)')]
    case ES_BO = 'es_BO';

    /**
     * Spanish (Canary Islands).
     */
    #[Label('Spanish (Canary Islands)')]
    case ES_IC = 'es_IC';

    /**
     * Spanish (Ceuta & Melilla).
     */
    #[Label('Spanish (Ceuta & Melilla)')]
    case ES_EA = 'es_EA';

    /**
     * Spanish (Chile).
     */
    #[Label('Spanish (Chile)')]
    case ES_CL = 'es_CL';

    /**
     * Spanish (Colombia).
     */
    #[Label('Spanish (Colombia)')]
    case ES_CO = 'es_CO';

    /**
     * Spanish (Costa Rica).
     */
    #[Label('Spanish (Costa Rica)')]
    case ES_CR = 'es_CR';

    /**
     * Spanish (Cuba).
     */
    #[Label('Spanish (Cuba)')]
    case ES_CU = 'es_CU';

    /**
     * Spanish (Dominican Republic).
     */
    #[Label('Spanish (Dominican Republic)')]
    case ES_DO = 'es_DO';

    /**
     * Spanish (Ecuador).
     */
    #[Label('Spanish (Ecuador)')]
    case ES_EC = 'es_EC';

    /**
     * Spanish (El Salvador).
     */
    #[Label('Spanish (El Salvador)')]
    case ES_SV = 'es_SV';

    /**
     * Spanish (Equatorial Guinea).
     */
    #[Label('Spanish (Equatorial Guinea)')]
    case ES_GQ = 'es_GQ';

    /**
     * Spanish (Guatemala).
     */
    #[Label('Spanish (Guatemala)')]
    case ES_GT = 'es_GT';

    /**
     * Spanish (Honduras).
     */
    #[Label('Spanish (Honduras)')]
    case ES_HN = 'es_HN';

    /**
     * Spanish (Mexico).
     */
    #[Label('Spanish (Mexico)')]
    case ES_MX = 'es_MX';

    /**
     * Spanish (Nicaragua).
     */
    #[Label('Spanish (Nicaragua)')]
    case ES_NI = 'es_NI';

    /**
     * Spanish (Panama).
     */
    #[Label('Spanish (Panama)')]
    case ES_PA = 'es_PA';

    /**
     * Spanish (Paraguay).
     */
    #[Label('Spanish (Paraguay)')]
    case ES_PY = 'es_PY';

    /**
     * Spanish (Peru).
     */
    #[Label('Spanish (Peru)')]
    case ES_PE = 'es_PE';

    /**
     * Spanish (Philippines).
     */
    #[Label('Spanish (Philippines)')]
    case ES_PH = 'es_PH';

    /**
     * Spanish (Puerto Rico).
     */
    #[Label('Spanish (Puerto Rico)')]
    case ES_PR = 'es_PR';

    /**
     * Spanish (Spain).
     */
    #[Label('Spanish (Spain)')]
    case ES_ES = 'es_ES';

    /**
     * Spanish (United States).
     */
    #[Label('Spanish (United States)')]
    case ES_US = 'es_US';

    /**
     * Spanish (Uruguay).
     */
    #[Label('Spanish (Uruguay)')]
    case ES_UY = 'es_UY';

    /**
     * Spanish (Venezuela).
     */
    #[Label('Spanish (Venezuela)')]
    case ES_VE = 'es_VE';

    /**
     * Swahili (Kenya).
     */
    #[Label('Swahili (Kenya)')]
    case SW_KE = 'sw_KE';

    /**
     * Swahili (Tanzania).
     */
    #[Label('Swahili (Tanzania)')]
    case SW_TZ = 'sw_TZ';

    /**
     * Swahili (Uganda).
     */
    #[Label('Swahili (Uganda)')]
    case SW_UG = 'sw_UG';

    /**
     * Swedish (Åland Islands).
     */
    #[Label('Swedish (Åland Islands)')]
    case SV_AX = 'sv_AX';

    /**
     * Swedish (Finland).
     */
    #[Label('Swedish (Finland)')]
    case SV_FI = 'sv_FI';

    /**
     * Swedish (Sweden).
     */
    #[Label('Swedish (Sweden)')]
    case SV_SE = 'sv_SE';

    /**
     * Tagalog.
     */
    #[Label('Tagalog')]
    case TL = 'tl';

    /**
     * Tagalog (Philippines).
     */
    #[Label('Tagalog (Philippines)')]
    case TL_PH = 'tl_PH';

    /**
     * Tamil (India).
     */
    #[Label('Tamil (India)')]
    case TA_IN = 'ta_IN';

    /**
     * Tamil (Malaysia).
     */
    #[Label('Tamil (Malaysia)')]
    case TA_MY = 'ta_MY';

    /**
     * Tamil (Singapore).
     */
    #[Label('Tamil (Singapore)')]
    case TA_SG = 'ta_SG';

    /**
     * Tamil (Sri Lanka).
     */
    #[Label('Tamil (Sri Lanka)')]
    case TA_LK = 'ta_LK';

    /**
     * Telugu (India).
     */
    #[Label('Telugu (India)')]
    case TE_IN = 'te_IN';

    /**
     * Thai (Thailand).
     */
    #[Label('Thai (Thailand)')]
    case TH_TH = 'th_TH';

    /**
     * Tibetan.
     */
    #[Label('Tibetan')]
    case BO = 'bo';

    /**
     * Tibetan (China).
     */
    #[Label('Tibetan (China)')]
    case BO_CN = 'bo_CN';

    /**
     * Tibetan (India).
     */
    #[Label('Tibetan (India)')]
    case BO_IN = 'bo_IN';

    /**
     * Tigrinya (Eritrea).
     */
    #[Label('Tigrinya (Eritrea)')]
    case TI_ER = 'ti_ER';

    /**
     * Tigrinya (Ethiopia).
     */
    #[Label('Tigrinya (Ethiopia)')]
    case TI_ET = 'ti_ET';

    /**
     * Tongan.
     */
    #[Label('Tongan')]
    case TO = 'to';

    /**
     * Tongan (Tonga).
     */
    #[Label('Tongan (Tonga)')]
    case TO_TO = 'to_TO';

    /**
     * Turkish (Cyprus).
     */
    #[Label('Turkish (Cyprus)')]
    case TR_CY = 'tr_CY';

    /**
     * Turkish (Turkey).
     */
    #[Label('Turkish (Turkey)')]
    case TR_TR = 'tr_TR';

    /**
     * Ukrainian (Ukraine).
     */
    #[Label('Ukrainian (Ukraine)')]
    case UK_UA = 'uk_UA';

    /**
     * Urdu (India).
     */
    #[Label('Urdu (India)')]
    case UR_IN = 'ur_IN';

    /**
     * Urdu (Pakistan).
     */
    #[Label('Urdu (Pakistan)')]
    case UR_PK = 'ur_PK';

    /**
     * Urunyabuligariya.
     */
    #[Label('Urunyabuligariya')]
    case BG = 'bg';

    /**
     * Uyghur (Arabic, China).
     */
    #[Label('Uyghur (Arabic, China)')]
    case UG_ARAB_CN = 'ug_Arab_CN';

    /**
     * Uyghur (Arabic).
     */
    #[Label('Uyghur (Arabic)')]
    case UG_ARAB = 'ug_Arab';

    /**
     * Uyghur (China).
     */
    #[Label('Uyghur (China)')]
    case UG_CN = 'ug_CN';

    /**
     * Uzbek (Afghanistan).
     */
    #[Label('Uzbek (Afghanistan)')]
    case UZ_AF = 'uz_AF';

    /**
     * Uzbek (Arabic, Afghanistan).
     */
    #[Label('Uzbek (Arabic, Afghanistan)')]
    case UZ_ARAB_AF = 'uz_Arab_AF';

    /**
     * Uzbek (Arabic).
     */
    #[Label('Uzbek (Arabic)')]
    case UZ_ARAB = 'uz_Arab';

    /**
     * Uzbek (Cyrillic, Uzbekistan).
     */
    #[Label('Uzbek (Cyrillic, Uzbekistan)')]
    case UZ_CYRL_UZ = 'uz_Cyrl_UZ';

    /**
     * Uzbek (Cyrillic).
     */
    #[Label('Uzbek (Cyrillic)')]
    case UZ_CYRL = 'uz_Cyrl';

    /**
     * Uzbek (Latin, Uzbekistan).
     */
    #[Label('Uzbek (Latin, Uzbekistan)')]
    case UZ_LATN_UZ = 'uz_Latn_UZ';

    /**
     * Uzbek (Latin).
     */
    #[Label('Uzbek (Latin)')]
    case UZ_LATN = 'uz_Latn';

    /**
     * Uzbek (Uzbekistan).
     */
    #[Label('Uzbek (Uzbekistan)')]
    case UZ_UZ = 'uz_UZ';

    /**
     * Vietnamese (Vietnam).
     */
    #[Label('Vietnamese (Vietnam)')]
    case VI_VN = 'vi_VN';

    /**
     * Welsh (United Kingdom).
     */
    #[Label('Welsh (United Kingdom)')]
    case CY_GB = 'cy_GB';

    /**
     * Western Frisian (Netherlands).
     */
    #[Label('Western Frisian (Netherlands)')]
    case FY_NL = 'fy_NL';

    /**
     * Yoruba.
     */
    #[Label('Yoruba')]
    case YO = 'yo';

    /**
     * Yoruba (Benin).
     */
    #[Label('Yoruba (Benin)')]
    case YO_BJ = 'yo_BJ';

    /**
     * Yoruba (Nigeria).
     */
    #[Label('Yoruba (Nigeria)')]
    case YO_NG = 'yo_NG';

    /**
     * Zulu (South Africa).
     */
    #[Label('Zulu (South Africa)')]
    case ZU_ZA = 'zu_ZA';
}
