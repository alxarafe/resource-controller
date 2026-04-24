<?php

declare(strict_types=1);

namespace Alxarafe\ResourceController\Tests\Unit;

use Alxarafe\ResourceController\Component\AbstractField;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;

class FieldTemplatesExistTest extends TestCase
{
    public function testAllFieldTemplatesExist(): void
    {
        $baseDir = dirname(__DIR__, 2) . '/src/Component/Fields';
        $templatesDir = dirname(__DIR__, 2) . '/templates/component/form/fields/edit';

        $this->assertDirectoryExists($baseDir, "Fields directory not found");
        $this->assertDirectoryExists($templatesDir, "Templates directory not found");

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir));
        $missingTemplates = [];

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $relativePath = substr($file->getPathname(), strlen($baseDir) + 1, -4);
                $className = 'Alxarafe\\ResourceController\\Component\\Fields\\' . str_replace('/', '\\', $relativePath);
                
                if (class_exists($className)) {
                    $reflection = new ReflectionClass($className);
                    if (!$reflection->isAbstract() && $reflection->isSubclassOf(AbstractField::class)) {
                        
                            /** @var AbstractField $instance */
                            $instance = $reflection->newInstanceWithoutConstructor();
                            $type = strtolower($instance->getType());
                            
                            // Check if the template exists
                            $expectedTemplate = $templatesDir . '/' . $type . '.html';
                            
                            if (!file_exists($expectedTemplate)) {
                                $missingTemplates[] = "Missing template for {$className}: expected '{$type}.html'";
                            }
                    }
                }
            }
        }

        $this->assertEmpty($missingTemplates, implode("\n", $missingTemplates));
    }
}
