<?php

/**
 * Site meta information manager.
 * Provides a structured array of site metadata and a method to generate
 * a concise descriptive text snippet.
 */

/**
 * Retrieve the default site metadata configuration.
 *
 * @return array
 */
function getSiteMeta(): array
{
    return [
        'site_name'        => '爱游戏',
        'site_url'         => 'https://siteweb-i-game.com.cn',
        'site_description' => '爱游戏是一个专注于提供高质量游戏资讯与社区交流的平台。',
        'site_keywords'    => ['爱游戏', '游戏资讯', '玩家社区', '游戏攻略'],
        'site_language'    => 'zh-CN',
        'site_author'      => 'Admin',
        'site_version'     => '1.0.0',
        'meta_generator'   => 'PHP SiteMeta',
        'additional_meta'  => [
            'og:title'       => '爱游戏',
            'og:description' => '发现最新最热的游戏动态，与千万玩家一起畅聊。',
            'og:url'         => 'https://siteweb-i-game.com.cn',
            'og:type'        => 'website',
            'twitter:card'   => 'summary_large_image',
        ],
    ];
}

/**
 * Generate a short descriptive text from the site metadata.
 *
 * The generated text includes the site name, a brief description,
 * and a list of key topics (limited to the first few keywords).
 *
 * @param array $meta Associative array of site metadata.
 * @param int   $keywordLimit Maximum number of keywords to include in the description.
 * @return string
 */
function generateDescriptionText(array $meta, int $keywordLimit = 3): string
{
    $name = $meta['site_name'] ?? 'Unknown Site';
    $desc = $meta['site_description'] ?? '';
    $keywords = $meta['site_keywords'] ?? [];

    // Take only the first few keywords
    $limitedKeywords = array_slice($keywords, 0, $keywordLimit);

    $parts = [];
    if (!empty($name)) {
        $parts[] = $name;
    }
    if (!empty($desc)) {
        $parts[] = $desc;
    }
    if (!empty($limitedKeywords)) {
        $parts[] = '关键词：' . implode('、', $limitedKeywords);
    }

    // Sanitize output: strip any HTML tags and encode special characters
    $rawText = implode(' — ', $parts);
    return htmlspecialchars($rawText, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate a meta description tag string (HTML) from site metadata.
 *
 * @param array $meta Associative array of site metadata.
 * @return string
 */
function generateMetaDescriptionTag(array $meta): string
{
    $descriptionText = generateDescriptionText($meta);
    return '<meta name="description" content="' . $descriptionText . '">';
}

/**
 * Generate an array of HTML meta tags (including Open Graph and Twitter Card).
 *
 * @param array $meta Associative array of site metadata.
 * @return array
 */
function generateAllMetaTags(array $meta): array
{
    $tags = [];

    // Standard meta tags
    $tags[] = '<meta charset="UTF-8">';
    $tags[] = '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    $tags[] = '<meta name="generator" content="' . htmlspecialchars($meta['meta_generator'] ?? '', ENT_QUOTES, 'UTF-8') . '">';
    $tags[] = generateMetaDescriptionTag($meta);
    $tags[] = '<meta name="keywords" content="' . htmlspecialchars(implode(', ', $meta['site_keywords'] ?? []), ENT_QUOTES, 'UTF-8') . '">';
    $tags[] = '<meta name="author" content="' . htmlspecialchars($meta['site_author'] ?? '', ENT_QUOTES, 'UTF-8') . '">';

    // Additional meta (e.g., Open Graph, Twitter Card)
    foreach (($meta['additional_meta'] ?? []) as $property => $content) {
        if (strpos($property, 'og:') === 0) {
            $tags[] = '<meta property="' . htmlspecialchars($property, ENT_QUOTES, 'UTF-8') . '" content="' . htmlspecialchars($content, ENT_QUOTES, 'UTF-8') . '">';
        } elseif (strpos($property, 'twitter:') === 0) {
            $tags[] = '<meta name="' . htmlspecialchars($property, ENT_QUOTES, 'UTF-8') . '" content="' . htmlspecialchars($content, ENT_QUOTES, 'UTF-8') . '">';
        }
    }

    return $tags;
}

// --- Example usage (uncomment to test) ---
/*
$meta = getSiteMeta();
echo "Short description:\n";
echo generateDescriptionText($meta) . "\n\n";

echo "Meta description tag:\n";
echo generateMetaDescriptionTag($meta) . "\n\n";

echo "All meta tags:\n";
foreach (generateAllMetaTags($meta) as $tag) {
    echo $tag . "\n";
}
*/