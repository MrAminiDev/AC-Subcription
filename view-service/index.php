<?php
if ((isset($_POST['userData']) and empty($_POST['userData'])) or !isset($_POST['userData'])){
    header('Location: /');
    exit();
}
$user=json_decode(base64_decode($_POST['userData']),true);

function bytesformat($bytes, $precision = 2): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?= $user['username'] ?> </title>
    <link href="build.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.6/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script defer src="qrcode.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.1/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs-i18n@2.4.0/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@ryangjchandler/alpine-clipboard@2.2.0/dist/alpine-clipboard.js"></script>
    <script>
        <?php $expireDateInit = empty($user['expire']) ? '∞' : date('Y-m-d H:i:s', $user['expire']); ?>
        <?php $dataUsage=empty($user['data_limit'])? 100 :(((($user['used_traffic']/$user['data_limit'])*100) < 100 ? number_format(($user['used_traffic']/$user['data_limit'])*100, 2) : 100));?>
        const resetInterval = "<?php echo $user['data_limit_reset_strategy']; ?>";
        <?php $expireDateVar = empty($user['expire']) ? '∞' : date('Y-m-d H:i:s', $user['expire']);?>

let appsJson = {
            "IOS": {
                "SingBox": {
                    "url": [
                        {
                            "name": "IOS 15+",
                            "url": "https://apps.apple.com/us/app/sing-box/id6451272673",
                            "best": false,
                        },
                    ],
                    "tutorial": "",
                    "autoImport": "sing-box://import-remote-profile?url="
                },
                "Streisand": {
                    "url": [
                        {
                            "name": "IOS 14+",
                            "url": "https://apps.apple.com/us/app/streisand/id6450534064",
                            "best": true
                        }
                    ],
                    "tutorial": ".../marzban-tutorial/streisand.MP4",
                    "autoImport": "streisand://install-config?url="
                },
                "FoXray": {
                    "url": [
                        {
                            "name": "IOS 16+",
                            "url": "https://apps.apple.com/us/app/foxray/id6448898396",
                            "best": false
                        }
                    ],
                    "tutorial": "../marzban-tutorial/foxray.mp4",
                    "autoImport": "foxray://install-config?url="
                },
                "V2Box": {
                    "url": [
                        {
                            "name": "IOS 14+",
                            "url": "https://apps.apple.com/us/app/v2box-v2ray-client/id6446814690",
                            "best": false
                        }
                    ],
                    "tutorial": "",
                    "autoImport": "v2box://install-config?url="
                },
                "Shadowrocket": {
                    "url": [
                        {
                            "name": "$3.99",
                            "url": "https://apps.apple.com/ca/app/shadowrocket/id932747118",
                            "best": false
                        }
                    ],
                    "tutorial": "../marzban-tutorial/v2box.MP4",
                    "autoImport":""
                }
            },
            "Android": {
                "SingBox": {
                    "url": [
                        {
                            "name": "",
                            "url": "https://github.com/SagerNet/sing-box/releases/download/v1.9.0-rc.3/SFA-1.9.0-rc.3-universal.apk",
                            "best": true,
                        }
                    ],
                    "tutorial": "",
                    "autoImport": "sing-box://import-remote-profile?url="
                },
                "v2rayNG": {
                    "url": [
                        {
                            "name": "GooglePlay",
                            "url": "https://play.google.com/store/apps/details?id=com.v2ray.ang",
                            "best": true
                        },
                        {
                            "name": "Github",
                            "url": "https://github.com/2dust/v2rayNG/releases/download/1.8.5/v2rayNG_1.8.5.apk",
                            "best": false
                        }
                    ],
                    "tutorial": "../marzban-tutorial/v2rayNG.mp4",
                    "autoImport": "v2rayng://install-config?url="
                },
                "NekoBox": {
                    "url": [
                        {
                            "name": "Arm64",
                            "url": "https://github.com/MatsuriDayo/NekoBoxForAndroid/releases/download/1.1.4/NB4A-1.1.4-arm64-v8a.apk",
                            "best": false
                        },
                        {
                            "name": "Armeabi",
                            "url": "https://github.com/MatsuriDayo/NekoBoxForAndroid/releases/download/1.1.4/NB4A-1.1.4-armeabi-v7a.apk",
                            "best": false
                        }
                    ],
                    "tutorial": ""
                },
                "HiddifyNext": {
                    "url": [
                        {
                            "name": "",
                            "url": "https://github.com/hiddify/hiddify-next/releases/download/v1.0.0/Hiddify-Android-universal.apk",
                            "best": false,
                        },
                    ],
                    "tutorial": "../marzban-tutorial/hiddify.mp4",
                    "autoImport": "",
                },
            }
            },
            "Windows": {
                "SingBox": {
                    "url": [
                        {
                            "name": "",
                            "url": "https://github.com/SagerNet/sing-box/releases/download/v1.9.0-rc.3/sing-box-1.9.0-rc.3-windows-amd64.zip",
                            "best": false,
                        },
                    ],
                    "tutorial": "",
                    "autoImport": "",
                },
                "nekoray": {
                    "url": [
                        {
                            "name": "",
                            "url": "https://github.com/MatsuriDayo/nekoray/releases/download/3.26/nekoray-3.26-2023-12-09-windows64.zip",
                            "best": true
                        }
                    ],
                    "tutorial": "../marzban-tutorial/nekoray.MP4"
                    "autoImport": ""
                },
                "v2rayN": {
                    "url": [
                        {
                            "name": "",
                            "url": "https://github.com/2dust/v2rayN/releases/download/6.60/zz_v2rayN-With-Core-SelfContained.7z",
                            "best": false
                        }
                    ],
                    "tutorial": "../marzban-tutorial/v2rayN.MP4"
                    "autoImport": "",
                }
            }
        };
        let langJson = {
            "en": {
                "active": "Active",
                "limited": "Limited",
                "expired": "Expired",
                "disabled": "Disabled",
                "dataUsage": "Data Usage: ",
                "expirationDate": "Expiration Date: ",
                "resetIntervalDay": "(Resets Every Day)",
                "resetIntervalWeek": "(Resets Every Week)",
                "resetIntervalMonth": "(Resets Every Month)",
                "resetIntervalYear": "(Resets Every Year)",
                "remainingDays": "Remaining Days: ",
                "remainingDaysSufix": " Days",
                "links": "Links",
                "apps": "Apps",
                "tutorials": "Tutorials",
                "subscription": "Subscription",
                "language": "Language",
                "settings": "Settings",
                "darkMode": "Dark mode",
                "copyAll": "Copy All",
                "proxy": "Proxy",
                "tutorial": "Tutorial",
                "download": "Download",
                "support": "Telegram Support",
                "import": "Import to app",
                "autoImport": "Auto Import",
            },
            "fa": {
                "active": "فعال",
                "limited": "تمام شده",
                "expired": "پایان یافته",
                "disabled": "غیرفعال",
                "dataUsage": "مصرف داده: ",
                "expirationDate": "تاریخ پایان: ",
                "resetIntervalDay": "(ریست روزانه)",
                "resetIntervalWeek": "(ریست هفتگی)",
                "resetIntervalMonth": "(ریست ماهانه)",
                "resetIntervalYear": "(ریست سالانه)",
                "remainingDays": "روزهای باقی‌مانده: ",
                "remainingDaysSufix": " روز",
                "links": "لینک‌ها",
                "apps": "برنامه‌ها",
                "tutorials": "آموزش‌ها",
                "subscription": "لینک اشتراک",
                "language": "زبان",
                "settings": "تنظیمات",
                "darkMode": "تم تیره",
                "copyAll": "کپی همه",
                "proxy": "پروکسی",
                "tutorial": "آموزش",
                "download": "دانلود",
                "support": "پشتیبانی تلگرام",
                "import": "افزودن به نرم افزار",
                "autoImport": "افزودن خودکار"
            },
            "ru": {
                "active": "активный",
                "limited": "ограниченное",
                "expired": "истекший",
                "disabled": "не активный",
                "dataUsage": "Использование данных: ",
                "expirationDate": "Дата окончания срока: ",
                "resetIntervalDay": "(сбрасывает каждый день)",
                "resetIntervalWeek": "(сбрасывается каждую неделю)",
                "resetIntervalMonth": "(сбрасывается каждый месяц)",
                "resetIntervalYear": "(сбрасывается каждый год)",
                "remainingDays": "оставшиеся дни: ",
                "remainingDaysSufix": " дни",
                "links": "ссылки",
                "apps": "Программы",
                "tutorials": "учебники",
                "subscription": "подписка",
                "language": "язык",
                "settings": "настройки",
                "darkMode": "тёмный режим",
                "copyAll": "скопировать все",
                "proxy": "прокси",
                "tutorial": "руководство",
                "download": "скачать",
                "support": "Поддержка телеграмм",
                "import": "Импортировать в приложение",
                "autoImport": "Автоматический импорт"
            }
        };
        let settings = {
            "darkMode": 1,
            "language": "en",
            "support": "", // can be telegram's support username link (exp: https://t.me/gozargah_marzban)
            "proxy": "" // hides proxy button
            //"proxy": "tg://socks?server=127.0.0.1&port=2085" // opens telegram directly
            //"proxy": "https://t.me/socks?server=127.0.0.1&port=2085"  // opens telegram in browser
        };
        settings.autoImportUserLink = getUserOSLink();
        
        document.addEventListener( 'alpine:init', () =>
        {
            darkMode = localStorage.getItem( "dark" ) ?? settings.darkMode;
            localStorage.setItem( "dark", darkMode );
        } );

        document.addEventListener( "alpine-i18n:ready", () =>
        {
            window.AlpineI18n.fallbackLocale = 'en';
            let locale = localStorage.getItem( "lang" ) ?? settings.language;
            window.AlpineI18n.create( locale, langJson );
            AlpineI18n.locale = locale;
            document.body.setAttribute( "dir", locale === "fa" ? "rtl" : "ltr" );
            if ( locale === "fa" ) $( document.body ).addClass( "font-[Vazirmatn]" );
            else $( document.body ).removeClass( "font-[Vazirmatn]" );
        } );
        
        function getUserOSLink ()
        {
            var platform = navigator.platform.toLowerCase();

            if ( platform.indexOf( 'win' ) !== -1 ) return appsJson.Windows.v2rayN.autoImport;
            else if ( platform.indexOf( 'iphone' ) !== -1 || platform.indexOf( 'ipad' ) !== -1 || platform.indexOf( 'ipod' ) !== -1 || platform.indexOf( 'mac' ) !== -1 ) return appsJson.IOS.V2Box;
            else if ( platform.indexOf( 'android' ) !== -1 || platform.indexOf( 'linux arm' ) !== -1 ) return appsJson.Android.v2rayNG.autoImport;
            else return '';
        }
        
    </script>
</head>
<body :class="settings.darkMode == 1 ? 'dark' : ''" x-data>
    <div class="relative flex min-h-screen flex-col justify-center overflow-hidden bg-main-light dark:bg-main-dark sm:py-6 transition" id="page-container">
        <div class="relative bg-sub-light dark:bg-sub-dark px-6 pt-10 pb-8 shadow-main-sh dark:shadow-main-sh-dark sm:mx-auto sm:rounded-xl sm:px-10 bg-clip-padding backdrop-filter backdrop-blur-xl bg-opacity-0 w-full max-w-2xl">
            <div class="mx-auto max-w-xl">
                <div class="flex flex-col sm:flex-row space-y-10 sm:space-y-0 sm:divide-x sm:rtl:divide-x-reverse sm:divide-blue-600/50">
                    <div class="basis-1/3 space-y-4 flex flex-col items-center py-3 sm:ltr:pr-9 sm:rtl:pl-9">
                        <img src="https://cdn.jsdelivr.net/gh/MuhammadAshouri/marzban-templates@master/template-01/images/marzban.svg" class="w-28"  alt=""/>
                        <span class="inline-block dark:text-white text-black font-semibold text-lg"><?php echo $user['username']; ?></span>
                        <span class="px-4 py-2 rounded-full inline-block shadow-md shadow-green-900 font-bold text-gray-200" x-data="{status: '<?php echo $user['status']; ?>'}" x-text="[status == 'active' ? $t('active') : status == 'limited' ? $t('limited') : status == 'expired' ? $t('expired') : $t('disabled')]" :class="[status == 'active' ? 'bg-blue-600' : status == 'limited' ? 'bg-red-600' : status == 'expired' ? 'bg-orange-600' : 'bg-gray-600']"></span>
                        <span class="flex cursor-pointer" onclick="openSettings()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-6 h-6 stroke-blue-600 drop-shadow-lg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-blue-600 drop-shadow-lg rtl:mr-2 ltr:ml-2" x-text="$t('settings')"></span>
                        </span>
                        <a class="rounded-md shadow-lg transition duration-300 bg-blue-600 text-white text-center text-lg py-2 w-4/5 cursor-pointer hover:shadow-xl stroke-blue-600" x-text="$t('proxy')" x-show="settings.proxy != ''" x-bind:href="settings.proxy"></a>
                    </div>
                    <div class="basis-2/3 flex flex-row items-center sm:ltr:pl-9 sm:rtl:pr-9">
                        <div class="data-usage w-full" x-data="progressBar" x-init="Alpine.data( 'progressBar', () => progressBar )">
                            <div class="dark:text-white text-black text-center"><span class="font-bold" x-text="$t('dataUsage')"></span><span dir="ltr"><?php echo bytesformat($user['used_traffic']).' / '. (empty($user['data_limit']) ? '∞' : bytesformat($user['data_limit'])); ?></span></div>
                            <div class="bg-gray-200 dark:bg-gray-900 rounded-full h-6 mt-5 drop-shadow-lg" role="progressbar" :aria-valuenow="width" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar rounded-full h-6 text-center dark:text-white text-black text-sm transition leading-6" :class="color" :style="`width: ${width}%; transition: width 2s;`" x-text="`${width}%`"></div>
                            </div>
                            <div class="dark:text-white text-black mt-10 text-center"><span class="font-bold" x-text="$t('expirationDate')"></span><span dir="ltr" x-data="{expireDate: ''}" x-init="Alpine.data( 'expireDate', expireDate = '<?=$expireDateVar?>' )" x-text="expireDate"></span></div><!--2023/06/31 10:43:59-->
                            <div class="dark:text-white text-black mt-3 text-sm text-center" x-text="resetInterval == 'year' ? $t('resetIntervalYear') : resetInterval == 'month' ? $t('resetIntervalMonth') : resetInterval == 'week' ? $t('resetIntervalWeek') : resetInterval == 'day' ? $t('resetIntervalDay') : ''"></div>
                            <div class="dark:text-white text-black mt-5 text-center"><span class="font-bold" x-text="$t('remainingDays')"></span><span><?php echo empty($user['expire']) ? '∞' : '(' . intval(($user['expire'] - time()) / (24 * 3600)) . ')'; ?></span><span x-text="$t('remainingDaysSufix')"></span></div>
                        </div>
                    </div>
                </div>
                <a class="rounded-md shadow-lg border-blue-600 border-2 text-white text-lg my-6 cursor-pointer h-16 flex flex-row justify-center items-center" x-show="settings.support != ''" x-bind:href="settings.support" target="_blank">
                    <svg class="h-12 ltr:mr-3 rtl:ml-3" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <style>
                                .cls-1,
                                .cls-3 {
                                    fill: none;
                                    stroke: rgb(37 99 235);
                                    stroke-linecap: round;
                                    stroke-linejoin: round;
                                }

                                .cls-1 {
                                    stroke-width: 2px;
                                }

                                .cls-2 {
                                    fill: rgb(37 99 235);
                                }

                            </style>
                        </defs>
                        <title />
                        <path class="cls-1" d="M8.25,25a5,5,0,0,1-4.9-6,5.12,5.12,0,0,1,5.08-4H10V25Z" />
                        <path class="cls-1" d="M23.75,25a5,5,0,0,0,4.9-6,5.12,5.12,0,0,0-5.08-4H22V25Z" />
                        <path class="cls-1" d="M7,15V13.82a9.11,9.11,0,0,1,6.91-9A9,9,0,0,1,25,13.61V15" />
                        <path class="cls-1" d="M10,25h0a3,3,0,0,0,3,3" />
                        <circle class="cls-2" cx="16" cy="28" r="1" />
                        <line class="cls-3" x1="7.5" x2="7.5" y1="15.5" y2="24.5" />
                        <rect class="cls-2" height="10" width="2" x="22" y="15" />
                    </svg>
                    <div x-text="$t('support')"></div>
                </a>
                <div class="shadow-box-shadow rounded-lg p-5 mt-7" x-data>
                    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="main-tab" data-tabs-toggle="#tabs-content" role="tablist">
                            <li class="flex-1" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg w-full transition" id="profile-tab" data-tabs-target="#links" type="button" role="tab" aria-controls="links" aria-selected="false" x-text="$t('links')"></button>
                            </li>
                            <li class="flex-1" role="presentation">
                                <button class="inline-block w-full p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 transition" id="apps-tab" data-tabs-target="#apps" type="button" role="tab" aria-controls="apps" aria-selected="false" x-text="$t('apps')"></button>
                            </li>
                        </ul>
                    </div>
                    <div id="tabs-content">
                        <div class="hidden" id="links" role="tabpanel" aria-labelledby="links-tab">
                            <ul class="list-none p-0 m-0">
                                <li class="flex px-3 mb-3 justify-between leading-[3.5rem] bg-black/20 rounded-md shadow-lg" x-data>
                                    <span class="font-semibold flex-1 dark:text-gray-200 text-black cursor-default" x-text="$t('subscription')"></span>
                                    <div class="flex justify-between items-center">
                                        <div class="w-8 h-8 ltr:mr-3 rtl:ml-3 cursor-pointer" x-data="{copyColor: 'stroke-blue-600'}" @click="() => { navigator.clipboard.writeText( '<?php echo $user['subscription_url']; ?>' ); copyColor = 'stroke-green-600'; setTimeout(() => copyColor = 'stroke-blue-600', 2000); }">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="dark:hover:stroke-gray-300 hover:stroke-gray-800 transition-colors" :class="copyColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184" />
                                            </svg>
                                        </div>
                                        <div class="w-8 h-8 cursor-pointer qr-button" data-link="<?=$user['subscription_url'] ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="stroke-blue-600 dark:hover:stroke-gray-300 hover:stroke-gray-800 transition-colors" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                                            </svg>
                                        </div>

                                    </div>
                                </li>

                                <?php if($user['status']==='active'): ?>
                                    <?php foreach($user['links'] as $link): ?>
                                        <li class="flex px-3 mb-1 justify-between leading-[3.5rem] hover:bg-black/10 rounded-md hover:shadow-lg transition duration-300" x-data="{link: '<?=$link; ?>'}">
                                            <span class="font-semibold flex-1 dark:text-gray-200 text-black cursor-default" x-text="getRemark(link)"></span>
                                            <div class="flex justify-between items-center">
                                                <div class="w-8 h-8 ltr:mr-3 rtl:ml-3 cursor-pointer" x-data="{copyColor: 'stroke-blue-600'}" @click="() => { navigator.clipboard.writeText( link ); copyColor = 'stroke-green-600'; setTimeout(() => copyColor = 'stroke-blue-600', 2000); }">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="dark:hover:stroke-gray-300 hover:stroke-gray-800 transition-colors" :class="copyColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184" />
                                                    </svg>
                                                </div>
                                                <div class="w-8 h-8 cursor-pointer qr-button" :data-link="link">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="stroke-blue-600 dark:hover:stroke-gray-300 hover:stroke-gray-800 transition-colors" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>

                                <li class="rounded-md shadow-lg transition duration-300 bg-blue-600 text-white text-center text-lg py-2 mt-3 cursor-pointer hover:shadow-2xl copyAllButton" x-text="$t('copyAll')"></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="hidden" id="apps" role="tabpanel" aria-labelledby="apps-tab">
                            <div class="flex sm:flex-row flex-col">
                                <div class="sm:basis-1/5 sm:rtl:ml-4 sm:ltr:mr-4">
                                    <ul class="flex sm:flex-col text-sm font-medium text-center" id="apps-tab" data-tabs-toggle="#apps-tabs-content" role="tablist">
                                        <template x-for="item in Object.keys(appsJson)">
                                            <li class="flex-grow mb-2" role="presentation">
                                                <button class="inline-block p-4 border-b-2 rounded-t-lg w-full transition" :id="item + '-tab'" :data-tabs-target="'#' + item" type="button" role="tab" :aria-controls="item" aria-selected="false" x-text="item"></button>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                                <div id="apps-tabs-content" class="sm:basis-4/5">
                                    
                                    <template x-for="item in Object.keys(appsJson)">
                                        <div class="hidden" :id="item" role="tabpanel" :aria-labelledby="item + '-tab'">
                                            <ul class="list-none p-0 m-0">
                                                <template x-for="app in Object.keys(appsJson[item]).reverse()">
                                                    <template x-for="subApp in appsJson[item][app].url">
                                                        <li :class="subApp.best ? 'bg-green-600/30 shadow-lg' : 'hover:bg-black/10 hover:shadow-lg'" class="flex px-3 mb-1 justify-between leading-[3.5rem] rounded-md transition duration-300" x-data="{link: subApp.url}">
                                                            <div class="flex flex-row items-center space-x-3 rtl:space-x-reverse cursor-default">
                                                                <span class="font-semibold flex-1 dark:text-gray-200 text-black" x-text="app"></span>
                                                                <span :class="subApp.best ? 'dark:text-gray-200 text-gray-800' : 'text-gray-600'" class="text-sm" x-text="subApp.name"></span>
                                                            </div>
                                                            <div class="flex justify-between items-center">
                                                                <a class="w-8 h-8 ltr:mr-3 rtl:ml-3 cursor-pointer" x-show="appsJson[item][app].autoImport != ''" :data-tooltip-target="'tooltip-import-' + app" :href="appsJson[item][app].autoImport + '<?=$user['subscription_url']?>'" :data-title="app">
                                                                    <svg fill="#000000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="stroke-blue-600 dark:hover:stroke-gray-300 hover:stroke-gray-800 transition-colors">
                                                                        <polyline id="primary" points="13 7 13 13 7 13" style="fill: none; "></polyline>
                                                                        <line id="primary-2" data-name="primary" x1="13" y1="13" x2="3" y2="3" style="fill: none; "></line>
                                                                        <path id="primary-3" data-name="primary" d="M13,3h7a1,1,0,0,1,1,1V20a1,1,0,0,1-1,1H4a1,1,0,0,1-1-1V13" style="fill: none; "></path>
                                                                    </svg>
                                                                </a>
                                                                <div :id="'tooltip-import-' + app" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                                    <span x-text="$t('import')"></span>
                                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                                </div>
                                                                <a class="w-8 h-8 ltr:mr-3 rtl:ml-3 cursor-pointer video-button" x-show="appsJson[item][app].tutorial != ''" :data-tooltip-target="'tooltip-tutorial-' + app" :data-link="appsJson[item][app].tutorial" :data-title="app">
                                                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="stroke-blue-600 dark:hover:stroke-gray-300 hover:stroke-gray-800 transition-colors">
                                                                        <polygon points="23 7 16 12 23 17 23 7" />
                                                                        <rect height="14" rx="2" ry="2" width="15" x="1" y="5" />
                                                                    </svg>
                                                                </a>
                                                                <div :id="'tooltip-tutorial-' + app" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                                    <span x-text="$t('tutorial')"></span>
                                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                                </div>
                                                                <a class="w-8 h-8 cursor-pointer" :data-tooltip-target="'tooltip-download-' + app" :href="link" target="_blank">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="stroke-blue-600 dark:hover:stroke-gray-300 hover:stroke-gray-800 transition-colors" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                                    </svg>
                                                                </a>
                                                                <div :id="'tooltip-download-' + app" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                                    <span x-text="$t('download')"></span>
                                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </template>
                                                </template>
                                            </ul>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="popup" class="fixed w-fit min-w-[20rem] max-h-[95vh] h-fit p-10 pt-7 shadow-dialog-shadow dark:shadow-2xl rounded-lg top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-main-light dark:bg-main-dark hidden">
        <h2 class="h-10 leading-[2.5rem] mb-4 inline-block font-semibold text-gray-950 dark:text-white"></h2>
        <a class="close absolute ltr:right-10 rtl:left-10 top-7 text-3xl cursor-pointer dark:text-white text-gray-950">&times;</a>
        <div class="content rounded-lg"></div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.6/flowbite.min.js"></script>

    <script>

        let progressBar = {
            width: '<?=$dataUsage?>',
            color: '<?php echo match (true){($dataUsage<40)=>'bg-green-600',($dataUsage<80)=>'bg-yellow-600',default=>'bg-red-500'} ?>'
        };

        let qrSize = $( window ).width() > 500 ? $( window ).height() > 500 ? 400 : $( window ).height() - 200 : $( window ).height() > 500 ? $( window ).width() - 100 : $( window ).height() - 200;
        $( window ).resize( function ()
        {
            qrSize = $( window ).width() > 500 ? $( window ).height() > 500 ? 400 : $( window ).height() - 200 : $( window ).height() > 500 ? $( window ).width() - 100 : $( window ).height() - 200;
        } );

        const popup = $( "#popup" );
        const qrButtons = $( '.qr-button' );
        const popupClose = $( '#popup > a.close' ).on( "click", () =>
        {
            popup.toggleClass( "hidden" );
            $( "#popup > .content" ).removeClass( "bg-white p-5" ).html( "" );
            $( "#popup > h2" ).html( "" );
            $( "#page-container" ).removeClass( 'blur-sm scale-110 -z-10' );
            setTimeout( () =>
            {
                $( document.body ).removeClass( 'overflow-hidden' );
            }, 200 );
        } );

        qrButtons.each( ( i, elem ) =>
        {
            $( elem ).on( 'click', () =>
            {
                const link = $( elem ).attr( "data-link" );
                $( "#popup > .content" ).addClass( "bg-white p-5" ).html( "" ).qrcode(
                    {
                        size: qrSize,
                        radius: 0.2,
                        text: link,
                        colorDark: "#000000",
                        colorLight: "#ffffff"
                    }
                );
                $( document.body ).addClass( 'overflow-hidden' );
                $( "#page-container" ).addClass( 'blur-sm scale-110 -z-10' );
                $( "#popup > h2" ).html( getRemark( link ) );
                popup.removeClass( "hidden" );
            } );
        } );

        $(".copyAllButton").on('click', async ( a ) => {
            let links = [];
            $(".qr-button").each((i, ele) => {
                let link = $(ele).attr("data-link");
                if (!link.startsWith("http")) links.push(link);
            });
            await navigator.clipboard.writeText(links.join("\n"));
            const thisObj = $(a.target).css("background", "#16a34a");
            setTimeout( () => thisObj.css( "background", "#2563eb" ), 1500 );
        });

        document.addEventListener( 'alpine:initialized', () =>
        {
            $( '.video-button' ).each( ( i, elem ) =>
            {
                $( elem ).on( 'click', () =>
                {
                    const title = $( elem ).attr( "data-title" );
                    const link = $( elem ).attr( "data-link" );
                    $( document.body ).addClass( 'overflow-hidden' );
                    $( "#page-container" ).addClass( 'blur-sm scale-110 -z-10' );
                    $( "#popup > .content" ).html( "" );
                    let video = $( "<video>" ).attr( "controls", "" ).addClass( "rounded-lg" );
                    $( "<source>" ).attr( { "src": link, "type": "video/mp4" } ).appendTo( video );
                    video.appendTo( "#popup > .content" );
                    $( "#popup > h2" ).html( title );
                    popup.removeClass( "hidden" );
                } );
            } );
        } );

        window.addEventListener( "alpine-i18n:locale-change", function ()
        {
            const locale = window.AlpineI18n.locale;
            document.body.setAttribute( "dir", locale === "fa" ? "rtl" : "ltr" );
            if ( locale === "fa" ) $( document.body ).addClass( "font-[vazirmatn]" );
            else $( document.body ).removeClass( "font-[vazirmatn]" );
        } );

        function getRemark ( link )
        {
            if ( link.startsWith( "http" ) ) return AlpineI18n.t( "subscription" );
            if ( link.includes( "vmess://" ) )
            {
                const config = JSON.parse( atob( link.replace( "vmess://", "" ) ) );
                return config.ps;
            }
            else return decodeURIComponent( link.split( "#" )[ 1 ] );
        }

        function changeLang ( ele )
        {
            localStorage.setItem( "lang", ele.value );
            window.AlpineI18n.locale = ele.value;
            document.body.setAttribute( "dir", ele.value === "fa" ? "rtl" : "ltr" );
            if ( ele.value === "fa" ) $( document.body ).addClass( "font-[Vazirmatn]" );
            else $( document.body ).removeClass( "font-[Vazirmatn]" );
        }

        function changeTheme(ele) {
            settings.darkMode = ele.checked ? 1 : 0;
            localStorage.setItem("dark", settings.darkMode);
            if (!ele.checked) $(document.body).removeClass("dark");
            else $(document.body).addClass("dark");
        }

        function openSettings ()
        {
            $( document.body ).addClass( 'overflow-hidden' );
            $( "#page-container" ).addClass( 'blur-sm scale-110 -z-10' );
            const content = $("#popup > .content");
            $( `<label for="default" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">` + AlpineI18n.t( 'language' ) + `</label>
<select id="default" class="bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" onchange="changeLang(this)">
  <option value="en"` + ( AlpineI18n.locale === "en" ? " selected" : "" ) + `>English</option>
  <option value="fa"` + ( AlpineI18n.locale === "fa" ? " selected" : "" ) + `>فارسی</option>
  <option value="ru"` + ( AlpineI18n.locale === "ru" ? " selected" : "" ) + `>Русский</option>
</select>
<label class="relative flex justify-between items-center cursor-pointer">
  <input type="checkbox" value="" class="sr-only peer"` + (settings.darkMode === 1 ? " checked" : "") + ` onchange="changeTheme(this)">
  <span class="text-sm font-medium text-gray-900 dark:text-white">` + AlpineI18n.t( "darkMode" ) + `</span>
  <div class="w-11 h-6 relative bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
</label>
`).appendTo( content );
            $( "#popup > h2" ).html( AlpineI18n.t( "settings" ) );
            popup.removeClass( "hidden" );
        }

    </script>
</body>
</html>
