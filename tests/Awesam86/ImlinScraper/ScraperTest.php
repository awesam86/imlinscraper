<?php
namespace Awesam86\ImlinScraper\Tests;

use Awesam86\ImlinScraper\Scraper;

class ScraperTest extends \PHPUnit_Framework_TestCase{
	
	protected $scraper;

	protected function setUp() {
        $this->scraper = new Scraper();
    }

    public function testInstance(){
        $this->assertInstanceOf('Awesam86\ImlinScraper\Scraper',$this->scraper);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetImagesDataUrlNull(){
        $this->scraper->GetImagesData(NULL);
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetImagesDataUrlEmptyString(){
        $this->scraper->GetImagesData('');
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetLinksDataUrlNull(){
        $this->scraper->GetLinksData(NULL);
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetLinksDataUrlEmptyString(){
        $this->scraper->GetLinksData('');
    }

}