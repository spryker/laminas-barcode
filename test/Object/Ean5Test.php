<?php

/**
 * @see       https://github.com/laminas/laminas-barcode for the canonical source repository
 * @copyright https://github.com/laminas/laminas-barcode/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-barcode/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Barcode\Object;

use Laminas\Barcode;

/**
 * @group      Laminas_Barcode
 */
class Ean5Test extends TestCommon
{
    protected function getBarcodeObject($options = null)
    {
        return new Barcode\Object\Ean5($options);
    }

    public function testType()
    {
        $this->assertSame('ean5', $this->object->getType());
    }

    public function testChecksum()
    {
        $this->assertSame(2, $this->object->getChecksum('45678'));
    }

    public function testSetText()
    {
        $this->object->setText('45678');
        $this->assertSame('45678', $this->object->getRawText());
        $this->assertSame('45678', $this->object->getText());
        $this->assertSame('45678', $this->object->getTextToDisplay());
    }

    public function testSetTextWithout13Characters()
    {
        $this->object->setText('4567');
        $this->assertSame('4567', $this->object->getRawText());
        $this->assertSame('04567', $this->object->getText());
        $this->assertSame('04567', $this->object->getTextToDisplay());
    }

    public function testSetTextWithoutChecksumHasNoEffect()
    {
        $this->object->setText('45678');
        $this->object->setWithChecksum(false);
        $this->assertSame('45678', $this->object->getRawText());
        $this->assertSame('45678', $this->object->getText());
        $this->assertSame('45678', $this->object->getTextToDisplay());
    }

    public function testSetTextWithSpaces()
    {
        $this->object->setText(' 45678 ');
        $this->assertSame('45678', $this->object->getRawText());
        $this->assertSame('45678', $this->object->getText());
        $this->assertSame('45678', $this->object->getTextToDisplay());
    }

    public function testSetTextWithChecksumNotDisplayed()
    {
        $this->object->setText('45678');
        $this->object->setWithChecksumInText(false);
        $this->assertSame('45678', $this->object->getRawText());
        $this->assertSame('45678', $this->object->getText());
        $this->assertSame('45678', $this->object->getTextToDisplay());
    }

    public function testCheckGoodParams()
    {
        $this->object->setText('45678');
        $this->assertTrue($this->object->checkParams());
    }


    public function testGetKnownWidthWithoutOrientation()
    {
        $this->object->setText('45678');
        $this->assertEquals(68, $this->object->getWidth());
        $this->object->setWithQuietZones(false);
        $this->assertEquals(48, $this->object->getWidth(true));
    }

    public function testCompleteGeneration()
    {
        $this->object->setText('45678');
        $this->object->draw();
        $instructions = $this->loadInstructionsFile('Ean5_45678_instructions');
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithBorder()
    {
        $this->object->setText('45678');
        $this->object->setWithBorder(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
                'Ean5_45678_border_instructions');
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithOrientation()
    {
        $this->object->setText('45678');
        $this->object->setOrientation(60);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
                'Ean5_45678_oriented_instructions');
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithBorderWithOrientation()
    {
        $this->object->setText('45678');
        $this->object->setOrientation(60);
        $this->object->setWithBorder(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
                'Ean5_45678_border_oriented_instructions');
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testGetDefaultHeight()
    {
        // Checksum activated => text needed
        $this->object->setText('45678');
        $this->assertEquals(62, $this->object->getHeight(true));
    }
}
