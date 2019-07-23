<?php
// ./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/MicrosoftLinksRefreshTest.php
declare(strict_types=1);

namespace A1rPun;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class MicrosoftLinksRefreshTest extends TestCase
{
    private const REFRESH_RESPONSE = '<html><head><meta http-equiv="refresh" content="0"/></head><body></body></html>';
    private $vm;

    public function setUp(): void
    {
        $this->vm = new MicrosoftLinksRefresh();
    }

    /**
     * @test
     */
    public function it_returns_the_default_response_when_there_is_no_match()
    {
        $this->testUserAgent('abcdefghijKrakaboom');
        $this->testUserAgent('Mozilla/5.0 (X11; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0');
    }

    /**
     * @test
     */
    public function it_returns_a_refresh_page_when_using_an_office_app()
    {
        $this->testUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X) Excel/14.20.0', true);
        $this->testUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X) Word/14.20.0', true);
        $this->testUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X) PowerPoint/14.20.0', true);
        $this->testUserAgent('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E; ms-office)', true);
    }

    /**
     * @test
     */
    public function it_returns_the_default_response_when_using_outlook()
    {
        $this->testUserAgent('Microsoft Office/14.0 (Windows NT 6.0; Microsoft Outlook 14.0.4760; Pro)');
        $this->testUserAgent('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET4.0C; InfoPath.3; Microsoft Outlook 14.0.6131; ms-office; MSOffice 14)');
    }

    private function createRequest($userAgent)
    {
        $request = new Request();
        $request->headers->set('User-Agent', $userAgent);
        return $request;
    }

    private function createGetResponseEventMock(Request $request)
    {
        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')
            ->disableOriginalConstructor()
            ->setMethods(array('getRequest'))
            ->getMock();
        $event->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));
        return $event;
    }

    private function testUserAgent($userAgent, $assert = false)
    {
        $request = $this->createRequest($userAgent);
        $event   = $this->createGetResponseEventMock($request);
        $this->vm->onKernelRequest($event);

        if ($assert) {
            $this->assertEquals(200, $event->getResponse()->getStatusCode());
            $this->assertEquals(self::REFRESH_RESPONSE, $event->getResponse()->getContent());
            // $this->assertEquals('text/html', $event->getResponse()->headers->get('Content-Type'));
        } else {
            $this->assertEquals($request, $event->getRequest());
            $this->assertNull($event->getResponse());
        }
    }
}
