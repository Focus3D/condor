<?php

class LanguageControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_switches_language_to_english_us()
    {
        $applocale = 'en';
        $this->call('GET', "/lang/$applocale");

        $this->assertSessionHas('language', 'en');
        $this->assertSessionHas('applocale', $applocale);
        $this->assertResponseStatus(302);
    }

    /**
     * @test
     */
    public function it_switches_language_to_spanish_es()
    {
        $applocale = 'es';
        $this->call('GET', "/lang/$applocale");

        $this->assertSessionHas('language', 'es');
        $this->assertSessionHas('applocale', $applocale);
        $this->assertResponseStatus(302);
    }
}
