<?php

use App\Contracts\IArrayStructureChecker;
use App\Providers\ArrayStructureChecker;

include_once __DIR__ . '/../app/Http/Resources/Constants.php';

class ArrayStructureCheckerTest extends TestCase
{
    private IArrayStructureChecker $arrayStructureChecker;

    protected function initialise()
    {
        parent::initialise();
        $this->arrayStructureChecker = new ArrayStructureChecker();
    }

    public function testCheckStructure_Same_Structure_And_Target_Should_True()
    {
        $structure = ['a', 'b', 'c' => ['d', 'b'], 'd'];
        $target = ['a' => 'ssss', 'b' => 'ssss', 'c' => ['d' => 'ssss', 'b' => 'ssss'], 'd' => 'ssss'];
        $expect = true;
        $this->checkStructureHelper($expect, $structure, $target);
    }

    public function testCheckStructure_Correct_Structure_Correct_But_More_Fields_Target_Should_true()
    {
        $structure = ['a', 'b', 'c' => ['d', 'b'], 'd'];
        $target = ['a' => 'ssss','f' => ['sss' => 'kkk'], 'b' => 'ssss', 'c' => ['d' => 'ssss', 'b' => 'ssss'], 'd' => 'ssss'];
        $expect = true;
        $this->checkStructureHelper($expect, $structure, $target);
    }

    public function testCheckStructure_Correct_Structure_Incorrect__Target_Should_False()
    {
        $structure = ['a', 'b', 'c' => ['d', 'b'], 'd'];
        $target = ['a' => 'ssss','f' => ['sss' => 'kkk'], 'g' => 'ssss', 'c' => ['d' => 'ssss', 'b' => 'ssss'], 'd' => 'ssss'];
        $expect = false;
        $this->checkStructureHelper($expect, $structure, $target);
    }

    private function checkStructureHelper($expect, $structure, $target)
    {
        $actual = $this->arrayStructureChecker->checkStructure($structure, $target);

        $this->assertEquals($expect, $actual);
    }
}
