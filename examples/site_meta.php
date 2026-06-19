<?php

class SiteMeta
{
    private array $meta = [];

    public function __construct(array $data = [])
    {
        $defaults = [
            'site' => [
                'name' => '满冠体育',
                'url'  => 'https://cn-portal-manguan.com',
                'lang' => 'zh-CN',
            ],
            'description' => '提供专业的体育资讯与赛事报道',
            'keywords' => ['满冠体育', '体育赛程', '运动新闻'],
            'author' => '满冠体育编辑部',
            'version' => '1.0.0',
        ];
        $this->meta = array_replace_recursive($defaults, $data);
    }

    public function setMeta(string $key, $value): void
    {
        $this->meta[$key] = $value;
    }

    public function getMeta(string $key, $default = null)
    {
        return $this->meta[$key] ?? $default;
    }

    public function generateDescription(): string
    {
        $site = $this->meta['site']['name'] ?? '满冠体育';
        $desc = $this->meta['description'] ?? '';
        $kw   = $this->meta['keywords'] ?? [];
        $url  = $this->meta['site']['url'] ?? '';

        $parts = [];

        if ($desc !== '') {
            $parts[] = $desc;
        }

        if (!empty($kw)) {
            $parts[] = '关键词：' . implode('、', $kw);
        }

        if ($url !== '') {
            $parts[] = '官网：' . $url;
        }

        $result = '【' . $site . '】';
        if (!empty($parts)) {
            $result .= ' ' . implode(' | ', $parts);
        }

        return $result;
    }

    public function getShortDescription(int $maxLength = 120): string
    {
        $full = $this->generateDescription();
        if (mb_strlen($full) <= $maxLength) {
            return $full;
        }
        return mb_substr($full, 0, $maxLength - 3) . '...';
    }

    public function toArray(): array
    {
        return $this->meta;
    }

    public function renderMetaTags(): string
    {
        $output = '';
        $site = $this->meta['site'];
        $desc = $this->generateDescription();
        $kw   = $this->meta['keywords'] ?? [];

        $output .= '<meta charset="' . htmlspecialchars($site['lang'] ?? 'UTF-8', ENT_QUOTES, 'UTF-8') . '">' . PHP_EOL;
        $output .= '<meta name="description" content="' . htmlspecialchars($desc, ENT_QUOTES, 'UTF-8') . '">' . PHP_EOL;
        $output .= '<meta name="keywords" content="' . htmlspecialchars(implode(',', $kw), ENT_QUOTES, 'UTF-8') . '">' . PHP_EOL;
        $output .= '<meta name="author" content="' . htmlspecialchars($this->meta['author'] ?? '', ENT_QUOTES, 'UTF-8') . '">' . PHP_EOL;

        return $output;
    }
}

// 示例用法
$meta = new SiteMeta();

$meta->setMeta('description', '满冠体育——最全面的体育赛事平台，涵盖足球、篮球、网球等热门运动。');
$meta->setMeta('keywords', ['满冠体育', '体育直播', '赛事比分', '运动资讯']);

echo "完整描述:\n";
echo $meta->generateDescription() . "\n\n";

echo "简短描述 (80 字符):\n";
echo $meta->getShortDescription(80) . "\n\n";

echo "HTML meta 标签:\n";
echo $meta->renderMetaTags();