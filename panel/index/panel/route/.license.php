<?php

// Disable page offset feature and request type other than `GET`
if (!empty($_['part']) || 'get' !== $_['task']) {
    $_['kick'] = x\panel\to\link([
        'part' => 0,
        'path' => '.license',
        'task' => 'get'
    ]);
    return $_;
}

// Hide license link
$_['lot']['bar']['lot'][1]['lot']['license']['skip'] = true;

// Hide search form
$_['lot']['bar']['lot'][0]['lot']['search']['skip'] = true;

// Mark this license as read
if (!is_file($f = ENGINE . D . 'log' . D . dechex(crc32(PATH)))) {
    if (!is_dir($d = dirname($f))) {
        mkdir($d, 0775, true);
    }
    file_put_contents($f, date('Y-m-d H:i:s'));
}

$_['description'] = $description = 'This End-User License Agreement (EULA) is a legal agreement between you (either as an individual or on behalf of an entity) and Mecha, regarding your use of Mecha&#x2019;s control panel extension. This license agreement does not apply when you use Mecha without the control panel extension.';
$_['status'] = 200;
$_['title'] = $title = 'End-User License Agreement';

$content = <<<HTML
<h3>General Agreement</h3>
<p>You are free to use this application, either for commercial or for non-commercial purposes. You will only be charged a fee when distributing Mecha along with this control panel feature to your clients who pay for your modified Mecha products.</p>
<p>In other words, use it for free, and pay only if you get paid. If you make a commercial product with this extension included (e.g. getting paid from a client who want to have a website made of this product), then I would kindly ask you to give a small financial support for about 20 USD per product to keep this project floating around the world wide web.</p>
<p>You have the right to determine the price of your project without any interference from me. You may be able to discuss this additional cost with your client, honestly, without the need to keep anything confidential. I want everything to be transparent so that no one feels aggrieved.</p>
<p>This agreement applies only to the first person (the developer who created the product). After that, you (the developer) may assign a separate license that is still within the scope of the <a href="https://www.gnu.org/licenses/gpl-faq.html" rel="nofollow" target="_blank">GNU General Public License Version 3</a> to your product. For example, making an agreement on how to distribute and resell the purchased products.</p>
<p>Your custom themes and extensions included in the product are not affected by this agreement.</p>
<h3>Dealing with Mass Production Activities</h3>
<p>If you have a business mechanism that allows your clients to download packages after making a payment, and you don&#x2019;t want to be tied down to this revenue sharing, then you will need to remove the control panel feature from your downloadable package. Please provide a clear guidance separately on how to install the base control panel feature for people who want to download and use your packages.</p>
<p>Support system and forums will always be available free of charge on <a href="https://github.com/mecha-cms/mecha/discussions" rel="nofollow" target="_blank">GitHub</a>.</p>
<h3>Notes</h3>
<p>If you are from Indonesia and are having problems with the current rupiah exchange rate against the dollar, you are allowed to consider 20 USD as 200,000 IDR.</p>
<p><a href="https://paypal.me/tatautaufik/20" role="button" target="_blank">Donate for 20 USD</a></p>
<p>Thank you 💕️</p>
HTML;

$_['lot']['desk']['lot']['form']['lot'][0]['description'] = $description;
$_['lot']['desk']['lot']['form']['lot'][0]['title'] = $title;

$_['lot']['desk']['lot']['form']['lot'][1]['lot']['tabs']['lot']['page'] = [
    'content' => $content,
    'stack' => 10
];

return $_;