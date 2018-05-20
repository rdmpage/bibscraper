<?php

// Gets metadata from <meta> tags
// harvest OAI

require_once (dirname(__FILE__) . '/lib.php');
require_once (dirname(__FILE__) . '/utils.php');
require_once (dirname(__FILE__) . '/simplehtmldom_1_5/simple_html_dom.php');

// get list of articles for each issue

$issues = array(
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/400',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/400',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/401',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/401',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/402',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/402',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/403',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/403',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/405',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/405',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/406',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/406',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/408',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/408',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/410',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/410',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/419',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/419',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/420',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/420',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/421',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/421',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/422',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/422',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/424',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/424',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/426',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/426',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/428',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/428',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/431',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/431',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/433',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/433',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/434',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/434',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/436',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/436',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/437',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/437',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/438',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/438',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/481',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/481',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/592',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/592',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/647',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/647',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/650',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/650',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/368',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/368',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/392',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/392',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/397',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/397',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/398',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/398',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/399',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/399',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/412',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/412',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/414',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/414',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/415',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/415',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/417',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/417',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/418',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/418');

//$issues=array('http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/401');

//'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/401/showToc'

$issues = array(
/*'http://journals.fcla.edu/jon/issue/view/4758',
'http://journals.fcla.edu/jon/issue/view/4457',
'http://journals.fcla.edu/jon/issue/view/4363',
'http://journals.fcla.edu/jon/issue/view/4275',
'http://journals.fcla.edu/jon/issue/view/4251',
'http://journals.fcla.edu/jon/issue/view/4245',
'http://journals.fcla.edu/jon/issue/view/4230',
'http://journals.fcla.edu/jon/issue/view/4210',
'http://journals.fcla.edu/jon/issue/view/4125',
'http://journals.fcla.edu/jon/issue/view/4073',
'http://journals.fcla.edu/jon/issue/view/4051',
'http://journals.fcla.edu/jon/issue/view/4026',
'http://journals.fcla.edu/jon/issue/view/3996',
'http://journals.fcla.edu/jon/issue/view/3974',
'http://journals.fcla.edu/jon/issue/view/3963',
'http://journals.fcla.edu/jon/issue/view/3960',
'http://journals.fcla.edu/jon/issue/view/3959',
'http://journals.fcla.edu/jon/issue/view/3932',
'http://journals.fcla.edu/jon/issue/view/3911',
'http://journals.fcla.edu/jon/issue/view/3827',
'http://journals.fcla.edu/jon/issue/view/3804',
'http://journals.fcla.edu/jon/issue/view/3803',
'http://journals.fcla.edu/jon/issue/view/3795',
'http://journals.fcla.edu/jon/issue/view/3762',
'http://journals.fcla.edu/jon/issue/view/3761',
'http://journals.fcla.edu/jon/issue/view/3760',
'http://journals.fcla.edu/jon/issue/view/3759',
'http://journals.fcla.edu/jon/issue/view/3758',
'http://journals.fcla.edu/jon/issue/view/3300',
'http://journals.fcla.edu/jon/issue/view/3299',
'http://journals.fcla.edu/jon/issue/view/3298',
'http://journals.fcla.edu/jon/issue/view/3297',
'http://journals.fcla.edu/jon/issue/view/3296',
'http://journals.fcla.edu/jon/issue/view/3295',
'http://journals.fcla.edu/jon/issue/view/3294',
'http://journals.fcla.edu/jon/issue/view/3293',
'http://journals.fcla.edu/jon/issue/view/3292',
'http://journals.fcla.edu/jon/issue/view/3291',
'http://journals.fcla.edu/jon/issue/view/3290',
'http://journals.fcla.edu/jon/issue/view/3289',
'http://journals.fcla.edu/jon/issue/view/3288',
'http://journals.fcla.edu/jon/issue/view/3287',
'http://journals.fcla.edu/jon/issue/view/3286',
'http://journals.fcla.edu/jon/issue/view/3285',
'http://journals.fcla.edu/jon/issue/view/3284',
'http://journals.fcla.edu/jon/issue/view/3283',
'http://journals.fcla.edu/jon/issue/view/3282',
'http://journals.fcla.edu/jon/issue/view/3281',
'http://journals.fcla.edu/jon/issue/view/3280',
'http://journals.fcla.edu/jon/issue/view/3364',
'http://journals.fcla.edu/jon/issue/view/3278',
'http://journals.fcla.edu/jon/issue/view/3277',
'http://journals.fcla.edu/jon/issue/view/3276',
'http://journals.fcla.edu/jon/issue/view/3275',
'http://journals.fcla.edu/jon/issue/view/3363',
'http://journals.fcla.edu/jon/issue/view/3362',
'http://journals.fcla.edu/jon/issue/view/3361',
'http://journals.fcla.edu/jon/issue/view/3360',
'http://journals.fcla.edu/jon/issue/view/3270',
'http://journals.fcla.edu/jon/issue/view/3359',
'http://journals.fcla.edu/jon/issue/view/3268',
'http://journals.fcla.edu/jon/issue/view/3267',
'http://journals.fcla.edu/jon/issue/view/3266',
'http://journals.fcla.edu/jon/issue/view/3265',
'http://journals.fcla.edu/jon/issue/view/3264',
'http://journals.fcla.edu/jon/issue/view/3263',
'http://journals.fcla.edu/jon/issue/view/3262',
'http://journals.fcla.edu/jon/issue/view/3261',
'http://journals.fcla.edu/jon/issue/view/3260',
'http://journals.fcla.edu/jon/issue/view/3358',
'http://journals.fcla.edu/jon/issue/view/3357',
'http://journals.fcla.edu/jon/issue/view/3257',
'http://journals.fcla.edu/jon/issue/view/3256',
'http://journals.fcla.edu/jon/issue/view/3255',
'http://journals.fcla.edu/jon/issue/view/3254',
'http://journals.fcla.edu/jon/issue/view/3253',
'http://journals.fcla.edu/jon/issue/view/3252',
'http://journals.fcla.edu/jon/issue/view/3251',
'http://journals.fcla.edu/jon/issue/view/3250',
'http://journals.fcla.edu/jon/issue/view/3249',
'http://journals.fcla.edu/jon/issue/view/3248',
'http://journals.fcla.edu/jon/issue/view/3247',
'http://journals.fcla.edu/jon/issue/view/3246',
'http://journals.fcla.edu/jon/issue/view/3245',
'http://journals.fcla.edu/jon/issue/view/3244',
'http://journals.fcla.edu/jon/issue/view/3243',
'http://journals.fcla.edu/jon/issue/view/3242',
'http://journals.fcla.edu/jon/issue/view/3241',
'http://journals.fcla.edu/jon/issue/view/3240',
'http://journals.fcla.edu/jon/issue/view/3356',
'http://journals.fcla.edu/jon/issue/view/3238',
'http://journals.fcla.edu/jon/issue/view/3237',
'http://journals.fcla.edu/jon/issue/view/3236',
'http://journals.fcla.edu/jon/issue/view/3235',
'http://journals.fcla.edu/jon/issue/view/3234',
'http://journals.fcla.edu/jon/issue/view/3233',
'http://journals.fcla.edu/jon/issue/view/3232',
'http://journals.fcla.edu/jon/issue/view/3355',
'http://journals.fcla.edu/jon/issue/view/3230',
'http://journals.fcla.edu/jon/issue/view/3229',
'http://journals.fcla.edu/jon/issue/view/3228',
'http://journals.fcla.edu/jon/issue/view/3227',
'http://journals.fcla.edu/jon/issue/view/3226',
'http://journals.fcla.edu/jon/issue/view/3225',
'http://journals.fcla.edu/jon/issue/view/3224',
'http://journals.fcla.edu/jon/issue/view/3223',
'http://journals.fcla.edu/jon/issue/view/3222',
'http://journals.fcla.edu/jon/issue/view/3221',
'http://journals.fcla.edu/jon/issue/view/3354',
'http://journals.fcla.edu/jon/issue/view/3219',
'http://journals.fcla.edu/jon/issue/view/3218',
'http://journals.fcla.edu/jon/issue/view/3353',
'http://journals.fcla.edu/jon/issue/view/3216',
'http://journals.fcla.edu/jon/issue/view/3215',
'http://journals.fcla.edu/jon/issue/view/3214',
'http://journals.fcla.edu/jon/issue/view/3352',
'http://journals.fcla.edu/jon/issue/view/3212',
'http://journals.fcla.edu/jon/issue/view/3211',
'http://journals.fcla.edu/jon/issue/view/3210',
'http://journals.fcla.edu/jon/issue/view/3209',
'http://journals.fcla.edu/jon/issue/view/3208',
'http://journals.fcla.edu/jon/issue/view/3207',
'http://journals.fcla.edu/jon/issue/view/3206',
'http://journals.fcla.edu/jon/issue/view/3205',
'http://journals.fcla.edu/jon/issue/view/3204',
'http://journals.fcla.edu/jon/issue/view/3351',
'http://journals.fcla.edu/jon/issue/view/3202',
'http://journals.fcla.edu/jon/issue/view/3201',
'http://journals.fcla.edu/jon/issue/view/3200',
'http://journals.fcla.edu/jon/issue/view/3199',
'http://journals.fcla.edu/jon/issue/view/3198',
'http://journals.fcla.edu/jon/issue/view/3197',
'http://journals.fcla.edu/jon/issue/view/3196',
'http://journals.fcla.edu/jon/issue/view/3350',
'http://journals.fcla.edu/jon/issue/view/3349',
'http://journals.fcla.edu/jon/issue/view/3193',
'http://journals.fcla.edu/jon/issue/view/3192',
'http://journals.fcla.edu/jon/issue/view/3191',*/
'http://journals.fcla.edu/jon/issue/view/3190',
'http://journals.fcla.edu/jon/issue/view/3348',
'http://journals.fcla.edu/jon/issue/view/3188',
'http://journals.fcla.edu/jon/issue/view/3187',
'http://journals.fcla.edu/jon/issue/view/3186',
'http://journals.fcla.edu/jon/issue/view/3185',
'http://journals.fcla.edu/jon/issue/view/3184',
'http://journals.fcla.edu/jon/issue/view/3183',
'http://journals.fcla.edu/jon/issue/view/3182',
'http://journals.fcla.edu/jon/issue/view/3181',
'http://journals.fcla.edu/jon/issue/view/3180',
'http://journals.fcla.edu/jon/issue/view/3179',
'http://journals.fcla.edu/jon/issue/view/3178',
'http://journals.fcla.edu/jon/issue/view/3177',
'http://journals.fcla.edu/jon/issue/view/3176',
'http://journals.fcla.edu/jon/issue/view/3175',
'http://journals.fcla.edu/jon/issue/view/3174',
'http://journals.fcla.edu/jon/issue/view/3173',
'http://journals.fcla.edu/jon/issue/view/3172',
'http://journals.fcla.edu/jon/issue/view/3171',
'http://journals.fcla.edu/jon/issue/view/3170',
'http://journals.fcla.edu/jon/issue/view/3169',
'http://journals.fcla.edu/jon/issue/view/3168',
'http://journals.fcla.edu/jon/issue/view/3167',
'http://journals.fcla.edu/jon/issue/view/3166',
'http://journals.fcla.edu/jon/issue/view/3165',
'http://journals.fcla.edu/jon/issue/view/3164',
'http://journals.fcla.edu/jon/issue/view/3163',
'http://journals.fcla.edu/jon/issue/view/3162',
'http://journals.fcla.edu/jon/issue/view/3161',
'http://journals.fcla.edu/jon/issue/view/3160',
'http://journals.fcla.edu/jon/issue/view/3159',
'http://journals.fcla.edu/jon/issue/view/3158',
'http://journals.fcla.edu/jon/issue/view/3157',
'http://journals.fcla.edu/jon/issue/view/3156',
'http://journals.fcla.edu/jon/issue/view/3155',
'http://journals.fcla.edu/jon/issue/view/3154',
'http://journals.fcla.edu/jon/issue/view/3153',
'http://journals.fcla.edu/jon/issue/view/3152',
'http://journals.fcla.edu/jon/issue/view/3151',
'http://journals.fcla.edu/jon/issue/view/3150',
'http://journals.fcla.edu/jon/issue/view/3149',
'http://journals.fcla.edu/jon/issue/view/3148',
'http://journals.fcla.edu/jon/issue/view/3147',
'http://journals.fcla.edu/jon/issue/view/3146',
'http://journals.fcla.edu/jon/issue/view/3145',
'http://journals.fcla.edu/jon/issue/view/3144',
'http://journals.fcla.edu/jon/issue/view/3143',
'http://journals.fcla.edu/jon/issue/view/3142',
'http://journals.fcla.edu/jon/issue/view/3141',
'http://journals.fcla.edu/jon/issue/view/3140',
'http://journals.fcla.edu/jon/issue/view/3139',
'http://journals.fcla.edu/jon/issue/view/3138',
'http://journals.fcla.edu/jon/issue/view/3137',
'http://journals.fcla.edu/jon/issue/view/3136',
'http://journals.fcla.edu/jon/issue/view/3135',
'http://journals.fcla.edu/jon/issue/view/3134',
'http://journals.fcla.edu/jon/issue/view/3133',
'http://journals.fcla.edu/jon/issue/view/3132',
'http://journals.fcla.edu/jon/issue/view/3131',
'http://journals.fcla.edu/jon/issue/view/3130',
'http://journals.fcla.edu/jon/issue/view/3129',
'http://journals.fcla.edu/jon/issue/view/3128',
'http://journals.fcla.edu/jon/issue/view/3127',
'http://journals.fcla.edu/jon/issue/view/3126',
'http://journals.fcla.edu/jon/issue/view/3125',
'http://journals.fcla.edu/jon/issue/view/3124',
'http://journals.fcla.edu/jon/issue/view/3347'
);

$issues = array(
'http://search.informit.com.au/browsePublication;py=2014;vol=131;res=IELHSS;issn=0042-5184;iss=1'
);

$issues = array(
'https://biotaxa.org/rce/issue/view/4264',
'https://biotaxa.org/rce/issue/view/4264',
'https://biotaxa.org/rce/issue/view/4266',
'https://biotaxa.org/rce/issue/view/4266',
'https://biotaxa.org/rce/issue/view/4267',
'https://biotaxa.org/rce/issue/view/4267',
'https://biotaxa.org/rce/issue/view/4270',
'https://biotaxa.org/rce/issue/view/4270',
'https://biotaxa.org/rce/issue/view/4271',
'https://biotaxa.org/rce/issue/view/4271',
'https://biotaxa.org/rce/issue/view/4273',
'https://biotaxa.org/rce/issue/view/4273',
'https://biotaxa.org/rce/issue/view/4275',
'https://biotaxa.org/rce/issue/view/4275',
'https://biotaxa.org/rce/issue/view/4316',
'https://biotaxa.org/rce/issue/view/4316',
'https://biotaxa.org/rce/issue/view/4511',
'https://biotaxa.org/rce/issue/view/4511'
);

$issues = array(
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/10',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/100',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/101',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/102',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/103',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/104',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/105',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/106',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/107',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/108',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/109',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/11',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/110',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/111',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/112',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/113',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/114',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/115',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/116',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/117',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/118',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/119',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/12',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/120',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/121',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/122',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/123',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/124',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/126',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/127',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/128',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/129',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/13',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/130',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/131',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/132',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/133',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/134',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/135',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/136',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/137',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/138',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/14',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/140',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/141',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/142',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/143',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/144',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/145',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/146',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/147',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/148',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/149',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/15',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/150',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/151',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/152',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/153',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/154',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/155',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/156',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/157',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/158',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/159',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/16',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/160',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/161',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/162',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/163',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/164',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/165',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/166',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/167',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/168',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/169',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/170',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/171',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/172',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/173',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/174',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/175',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/176',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/177',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/178',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/179',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/180',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/181',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/182',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/183',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/184',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/185',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/187',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/188',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/189',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/19',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/190',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/191',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/192',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/193',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/194',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/195',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/196',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/198',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/199',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/200',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/201',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/202',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/203',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/204',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/205',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/206',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/207',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/208',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/209',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/210',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/211',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/212',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/213',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/214',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/215',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/216',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/217',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/218',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/219',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/22',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/220',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/221',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/222',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/223',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/224',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/225',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/226',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/227',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/228',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/229',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/23',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/230',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/231',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/232',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/233',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/234',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/235',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/236',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/237',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/238',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/239',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/240',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/241',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/242',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/243',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/244',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/245',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/246',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/247',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/248',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/249',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/25',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/250',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/251',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/252',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/253',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/254',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/255',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/256',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/257',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/258',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/259',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/260',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/261',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/262',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/263',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/264',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/265',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/266',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/267',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/27',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/28',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/29',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/30',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/32',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/33',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/34',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/35',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/36',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/37',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/38',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/39',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/40',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/41',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/44',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/44',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/45',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/46',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/47',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/48',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/49',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/5',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/50',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/52',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/53',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/54',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/55',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/57',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/58',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/59',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/6',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/60',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/61',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/62',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/63',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/64',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/67',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/68',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/69',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/7',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/70',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/71',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/72',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/73',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/74',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/75',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/76',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/77',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/78',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/79',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/80',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/81',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/82',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/83',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/84',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/85',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/86',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/87',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/88',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/89',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/9',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/90',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/91',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/92',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/93',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/94',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/95',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/96',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/97',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/98',
'http://www.ameghiniana.org.ar/index.php/ameghiniana/issue/view/99');

$issues=array(
'https://www.biotaxa.org/em/issue/view/1132',
'https://www.biotaxa.org/em/issue/view/1132',
'https://www.biotaxa.org/em/issue/view/1133',
'https://www.biotaxa.org/em/issue/view/1133',
'https://www.biotaxa.org/em/issue/view/1141',
'https://www.biotaxa.org/em/issue/view/1141',
'https://www.biotaxa.org/em/issue/view/1182',
'https://www.biotaxa.org/em/issue/view/1182',
'https://www.biotaxa.org/em/issue/view/1256',
'https://www.biotaxa.org/em/issue/view/1256',
'https://www.biotaxa.org/em/issue/view/1260',
'https://www.biotaxa.org/em/issue/view/1260',
'https://www.biotaxa.org/em/issue/view/1393',
'https://www.biotaxa.org/em/issue/view/1393',
'https://www.biotaxa.org/em/issue/view/1497',
'https://www.biotaxa.org/em/issue/view/1497',
'https://www.biotaxa.org/em/issue/view/1564',
'https://www.biotaxa.org/em/issue/view/1564',
'https://www.biotaxa.org/em/issue/view/1780',
'https://www.biotaxa.org/em/issue/view/1780',
'https://www.biotaxa.org/em/issue/view/1930',
'https://www.biotaxa.org/em/issue/view/1930',
'https://www.biotaxa.org/em/issue/view/2440',
'https://www.biotaxa.org/em/issue/view/2440',
'https://www.biotaxa.org/em/issue/view/2488',
'https://www.biotaxa.org/em/issue/view/2488',
'https://www.biotaxa.org/em/issue/view/2746',
'https://www.biotaxa.org/em/issue/view/2746',
'https://www.biotaxa.org/em/issue/view/3941',
'https://www.biotaxa.org/em/issue/view/3941',
'https://www.biotaxa.org/em/issue/view/4180',
'https://www.biotaxa.org/em/issue/view/4180',
'https://www.biotaxa.org/em/issue/view/4407',
'https://www.biotaxa.org/em/issue/view/4407',
'https://www.biotaxa.org/em/issue/view/4554',
'https://www.biotaxa.org/em/issue/view/4554',
'https://www.biotaxa.org/em/issue/view/4610',
'https://www.biotaxa.org/em/issue/view/4610',
'https://www.biotaxa.org/em/issue/view/4677',
'https://www.biotaxa.org/em/issue/view/4677',
'https://www.biotaxa.org/em/issue/view/4762',
'https://www.biotaxa.org/em/issue/view/4762',
'https://www.biotaxa.org/em/issue/view/5038'
);

$issues=array(
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/12555',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/12555',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/12696',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/12696',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/12739',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/12739',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/12740',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/12740',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/12742',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/12742',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/13532',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/13532',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/16684',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/16684',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/16688',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/16688',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/16689',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/16689',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/16692',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/16692',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/17476',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/17476',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/17477',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/17485',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/17485',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/17486',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/17486',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/17656',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/17656',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/17657',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/17657',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/18796',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/18796',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/20276',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/20276',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/20277',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/20277',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/20352',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/20352',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/20353',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/22608',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/22608',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/22773',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/22773',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/24502',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/24502',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/24503',
'http://www.raco.cat/index.php/ButlletiICHN/issue/view/24503'
);

$issues=array(
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/4719',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/4730',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/4737',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/4761',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/4765',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5885',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5968',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5970',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5973',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5974',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5975',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5977',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5978',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5979',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5981',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5984',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5985',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5986',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5987',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5989',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5990',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5991',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5992',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5993',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5998',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/5999',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6000',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6001',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6002',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6005',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6006',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6007',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6010',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6011',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6017',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6018',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6021',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6022',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6026',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6028',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6029',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6031',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6032',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6033',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6036',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6037',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6039',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6040',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6043',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6044',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6050',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6051',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6052',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6053',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6055',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6057',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6058',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6059',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6060',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6063',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6064',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6065',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6068',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6069',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6070',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6071',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6073',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6075',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6076',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6078',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6588',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/6595',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/7107',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/7107',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/7107',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/7155',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/7394',
'http://www.nelumbo-bsi.org/index.php/nlmbo/issue/view/7600'
);

foreach ($issues as $issue)
{
	$u = $issue;
	
	$u = $issue . '/showToc'; // OJS

	$html = get($u);
	
	$urls = array();
	
	if (preg_match_all('/<a href="(?<url>.*article\/view\/\d+)">/Uu', $html, $m))
	{
		foreach ($m['url'] as $url)
		{
			$urls[] = $url;
		}
	}
	
	if (preg_match_all('/<a href="(?<url>\/documentSummary;dn=\d+;res=IELHSS)"/', $html, $m))
	{
		foreach ($m['url'] as $url)
		{
			$urls[] = 'http://search.informit.com.au' . $url;
		}	
	}
		

	$count = 1;


	foreach ($urls as $url)
	{

		$html = get($url);

		$reference = new stdclass;
		$reference->authors = array();
		//$reference->id = $id;


		$dom = str_get_html($html);

		$metas = $dom->find('meta');

		/*
		foreach ($metas as $meta)
		{
			echo $meta->name . " " . $meta->content . "\n";
		}
		*/				

		foreach ($metas as $meta)
		{
			switch ($meta->name)
			{

				// DC

				case 'DC.title':
				case 'dc.Title':
					$reference->title =  $meta->content;
					$reference->title = preg_replace('/\s\s+/u', ' ', $reference->title);
					break;

				case 'DC.description':
				case 'DC.Description':
				case 'citation_abstract':
					$reference->abstract =  $meta->content;
					$reference->abstract = str_replace("\n", "", $reference->abstract);
					$reference->abstract = str_replace("&amp;", "&", $reference->abstract);
					$reference->abstract = preg_replace('/\s\s+/u', ' ', $reference->abstract);		
					$reference->abstract = preg_replace('/^\s+/u', '', $reference->abstract);		
					$reference->abstract = html_entity_decode($reference->abstract);
					break;
			
				case 'DC.Creator.PersonalName':
					if (!in_array($meta->content, $reference->authors))
					{
						$reference->authors[] =  $meta->content;
					}
					break;	
			
				case 'DC.Source.ISSN':
					$reference->issn =  $meta->content;
					break;	

				// eprints

				case 'eprints.creators_name':
					$author = $meta->content;

					// clean
					if (preg_match('/^(?<lastname>.*),\s+(?<firstname>[A-Z][A-Z]+)$/u', $author, $m))
					{
						$parts = str_split($m['firstname']);
						$author = $m['lastname'] . ', ' . join(". ", $parts) . '.';
					}
					if (!in_array($author, $reference->authors))
					{
						$reference->authors[] =  $meta->content;
					}
					break;

				case 'eprints.publication':
					$reference->journal =  $meta->content;
					break;

				case 'eprints.issn':
					$reference->issn =  $meta->content;
					break;


				case 'eprints.volume':
					$reference->volume =  $meta->content;
					break;

				case 'eprints.pagerange':
					$pages =  $meta->content;
					$parts = explode("-", $pages);
					if (count($parts) > 1)
					{
						$reference->spage = $parts[0];
						$reference->epage = $parts[1];
					}
					else
					{
						$reference->spage = $pages;
					}
					break;

				case 'eprints.date':
					if (preg_match('/^[0-9]{4}$/', $meta->content))
					{
						$reference->year = $meta->content;
					}

					if (preg_match('/^(?<year>[0-9]{4})\//', $meta->content, $m))
					{
						$reference->year = $m['year'];
					}
					break;

				case 'eprints.document_url':
					$reference->pdf =  urldecode($meta->content);
					break;

				// Google	
				case 'citation_authors':
				case 'citation_author':
	//							$reference->authors[] =  mb_convert_case($meta->content, MB_CASE_TITLE);

					if (!in_array($meta->content, $reference->authors))
					{
						$reference->authors[] =  $meta->content;
					}
					break;

				case 'citation_title':
					$reference->title = trim($meta->content);
					$reference->title = preg_replace('/\s\s+/u', ' ', $reference->title);
					break;

				case 'citation_doi':
					$reference->doi =  $meta->content;
					break;

				case 'citation_journal_title':
					$reference->journal =  $meta->content;
					$reference->genre = 'article';
					break;

				case 'citation_issn':
					if (!isset($reference->issn))
					{
						$reference->issn =  $meta->content;
					}
					break;

				case 'citation_volume':
					$reference->volume =  $meta->content;
					break;

				case 'citation_issue':
					$reference->issue =  $meta->content;
					break;

				case 'citation_firstpage':
					$reference->spage =  $meta->content;
			
					if (preg_match('/(?<spage>\d+)[-|-](?<epage>\d+)/u', $meta->content, $m))
					{
						$reference->spage =  $m['spage'];
						$reference->epage =  $m['epage'];
					}
					break;

				case 'citation_lastpage':
					$reference->epage =  $meta->content;
					break;

				case 'citation_abstract_html_url':
					$reference->url =  $meta->content;
					break;

				case 'citation_pdf_url':
					$reference->pdf =  $meta->content;
					break;
			
				case 'citation_fulltext_html_url':
					$reference->pdf =  $meta->content;
					//$reference->pdf = str_replace('/view/', '/download/', $reference->pdf);
					break;
			

				case 'citation_date':
					if (preg_match('/^[0-9]{4}$/', $meta->content))
					{
						$reference->year = $meta->content;
					}

					if (preg_match('/^(?<year>[0-9]{4})\//', $meta->content, $m))
					{
						$reference->year = $m['year'];
					}
					break;

				case 'DC.Date':
				case 'dc.Date':
					$reference->date = $meta->content;
					break;


				default:
					break;
			}
		}		
		//print_r($reference);

		if ($reference->issn == '2413-3299')
		{
			unset($reference->doi);
			$reference->issn = '1815-8242';
		}				

		if (isset($reference->pdf))
		{
			$reference->pdf = str_replace('/view/', '/download/', $reference->pdf);
		}

		if (($year != '') && !isset($reference->year))
		{
			$reference->year = $year;
		}


		if (isset($reference->issue))
		{
			if ($reference->issue == 0)
			{
				unset($reference->issue);
			}
		}

		// Dugesiana
		// Vol. 5, Núm. 2 (1998)
		if (preg_match('/Vol. \d+, Núm. \d+ \((?<year>[0-9]{4})\)/u', $html, $m))
		{
			$reference->year = $m['year'];
		}


		echo reference_to_ris($reference);

		// Give server a break every 10 items
		if (($count++ % 10) == 0)
		{
			$rand = rand(1000000, 3000000);
			echo "\n...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
			usleep($rand);
		}
	}
}


?>