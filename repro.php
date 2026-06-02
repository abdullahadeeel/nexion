<?php
class Engine {
    public function compile(string $content): string
    {
        $content = preg_replace('/\{\{\s*(.*?)\s*\}\}/', '<?php echo htmlspecialchars($1); ?>', $content);
        $content = preg_replace('/@extends\(\'(.*?)\'\)/', '<?php $this->layout = \'$1\'; ?>', $content);
        $content = preg_replace('/@section\(\'(.*?)\'\)/', '<?php $this->startSection(\'$1\'); ?>', $content);
        $content = preg_replace('/@endsection/', '<?php $this->endSection(); ?>', $content);
        $content = preg_replace('/@yield\(\'(.*?)\'\)/', '<?php echo $this->yieldSection(\'$1\'); ?>', $content);
        $content = preg_replace('/@if\(((?:[^()]*|\([^()]*\))*)\)/', '<?php if($1): ?>', $content);
        $content = preg_replace('/@else/', '<?php else: ?>', $content);
        $content = preg_replace('/@endif/', '<?php endif; ?>', $content);
        $content = preg_replace('/@foreach\(((?:[^()]*|\([^()]*\))*)\)/', '<?php foreach($1): ?>', $content);
        $content = preg_replace('/@endforeach/', '<?php endforeach; ?>', $content);
        return $content;
    }
}
$engine = new Engine();
$content = "@if(isset(\$success))";
echo $engine->compile($content) . "\n";
