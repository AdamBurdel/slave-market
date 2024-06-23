<?php

namespace SlaveMarket\Helper;

use PHPUnit\Framework\TestCase;

class DateTimeRoundHelperTest extends TestCase
{

    public function testRoundHoursString()
    {
        $roundedDateTime = DateTimeRoundHelper::roundHoursString('2017-01-01 01:30:00');
        $this->assertEquals('2017-01-01 01:00:00', $roundedDateTime);
    }
}
