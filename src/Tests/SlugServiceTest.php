<?php

use PHPUnit\Framework\TestCase;
use Carbe\Petitcreuxv2\Helpers\SlugService;

class SlugServiceTest extends TestCase {
    
    public function testSlugService() :void {
        $title = "Croissant au jambon";
        $slug = SlugService::generateSlug($title);
        $this->assertEquals("croissant-au-jambon", $slug);
    }

    public function testSlugServiceWithcharacters() :void {
        $title = "Pâtes au jambon";
        $slug = SlugService::generateSlug($title);
        $this->assertEquals("pates-au-jambon", $slug);
    }
}

// .\vendor\bin\phpunit.bat .\src\Tests\SlugServiceTest.php