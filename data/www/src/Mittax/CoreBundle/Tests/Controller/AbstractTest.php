<?php

namespace Mittax\CoreBundle\Tests\Controller;

use Mittax\WsseBundle\Tests\AbstractKernelTestCase;

class AbstractTest extends AbstractKernelTestCase
{
    /**
     * @var string
     */
    protected $_bundle = 'emotico';

    /**
     * @var string
     */
    protected $_sampelData;

    /**
     * @param string $bundle
     */
    public function setBundle(string $bundle)
    {
        $this->_bundle = $bundle;
    }

    /**
     * Setup
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @return string
     */
    public function getSampleDataResponse() : string
    {
        $uri = $this->_serverUrl . '/' . $this->_bundle . '/sample/test';

        $response = $this->_requestClient->request('GET', $uri, $this->_requestClient->getWsseHeaderRequestOtionsByUsername($this->_adminuser));

        $sampleDataResponse = (string)$response->getBody();

        return $sampleDataResponse;
    }


    /**
     * test gitting a list
     */
    public function testGetWsseDataHeaderOptions()
    {
        $wsseDataHeaderOptions = $this->getWsseHeaderOptionsForBodyDataByUsername($this->_adminuser);

        $this->assertNotEmpty($wsseDataHeaderOptions);
    }


    /**
     * test gitting a list
     */
    public function testGet()
    {
        $wsseDataHeaderOptions = $this->getWsseHeaderOptionsForBodyDataByUsername($this->_adminuser);

        $responseText = $this->makeRequestWithSampleDataResponse('POST', $this->_bundle, $wsseDataHeaderOptions);

        $responseAsObject = json_decode($responseText);

        $id = $responseAsObject->content->return->id;

        /**
         * test getting a list
         */
        $response = $this->_requestClient->request("GET", $this->_serverUrl  . '/' . $this->_bundle, $this->_wsseHeaderOptions);

        $responseText = (string)$response->getBody();

        $responseAsObject = json_decode($responseText);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertGreaterThan(0, count($responseAsObject));
    }

    /**
     * Test Post with missing content type
     *
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testUnsupportedMediaTypeOnPost()
    {
        $this->_requestClient->request('POST', $this->_serverUrl  . '/' . $this->_bundle, $this->_wsseHeaderOptions, $this->getSampleDataResponse());

        $this->expectExceptionMessage('415');
    }

    /**
     * test the post method to create new objects
     */
    public function testPost()
    {
        $wsseDataHeaderOptions = $this->getWsseHeaderOptionsForBodyDataByUsername($this->_adminuser);

        $responseText = $this->makeRequestWithSampleDataResponse('POST', $this->_bundle, $wsseDataHeaderOptions);

        $responseAsObject = json_decode($responseText);

        /**
         * Test if a last insert id is available in the response, so the database insert was successful
         */
        $this->assertNotEmpty($responseAsObject->content->return->id);

        /**
         * Test if the request was successfull
         */
        $this->assertContains('success', $responseText);
    }

    /**
     * test if the validation is working
     */
    public function testPostWithValidationError()
    {
        $path = $this->_serverUrl  . '/' . $this->_bundle;

        $data = '{"title":"sds<d dsad"}';

        $wsseDataHeaderOptions = $this->getWsseHeaderOptionsForBodyDataByUsername($this->_adminuser);

        $response = $this->_requestClient->request('POST', $path, $wsseDataHeaderOptions, $data);

        $responseText = (string)$response->getBody();

        $this->assertContains('This value should not be blank', $responseText);

        $responseAsObject = json_decode($responseText);

        $this->assertEquals(400, $responseAsObject->status);
    }

    /**
     * test if the validation is working
     */
    public function testPut()
    {
        $wsseDataHeaderOptions = $this->getWsseHeaderOptionsForBodyDataByUsername($this->_adminuser);

        $responseText = $this->makeRequestWithSampleDataResponse('POST', $this->_bundle, $wsseDataHeaderOptions);

        $responseAsObject = json_decode($responseText);

        $id = $responseAsObject->content->return->id;

        $path = $this->_bundle . '/' . $id;

        $wsseDataHeaderOptions = $this->getWsseHeaderOptionsForBodyDataByUsername($this->_adminuser);

        $responseText = $this->makeRequestWithSampleDataResponse('PUT', $path, $wsseDataHeaderOptions);

        $responseAsObject = json_decode($responseText);

        /**
         * Test status
         */
        $this->assertEquals(200, $responseAsObject->status);

        /**
         * Test if the objectid from the request is the same as in the response
         */
        $this->assertEquals($id, $responseAsObject->content->return->id);
    }

    /**
     * Test if gettimg an item by id is successfull
     */
    public function testGetById()
    {
        /**
         * create an id by post a new item
         */
        $wsseDataHeaderOptions = $this->getWsseHeaderOptionsForBodyDataByUsername($this->_adminuser);

      
        $responseText = $this->makeRequestWithSampleDataResponse('POST', $this->_bundle , $wsseDataHeaderOptions);

        $responseAsObject = json_decode($responseText);

        $id = $responseAsObject->content->return->id;

        /**
         * Test the get by id now
         */
        $uri = $this->_serverUrl  . '/' . $this->_bundle . '/' . $id;

        $response = $this->_requestClient->request('GET', $uri, $this->_requestClient->getWsseHeaderRequestOtionsByUsername($this->_adminuser));

        $responseText = (string)$response->getBody();

        $this->assertEquals(200, $response->getStatusCode());

        $responseAsObject = json_decode($responseText);

        $this->assertEquals($id,$responseAsObject->id);
    }

    /**
     * Test if getting an item by id will fail
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testFailGetById()
    {
        $uri = $this->_serverUrl  . '/' . $this->_bundle .'/bullshit';

        $header = $this->_requestClient->getWsseHeaderRequestOtionsByUsername($this->_adminuser);

        $this->_requestClient->request('GET', $uri, $header);

        $this->expectExceptionCode(404);
    }

    /**
     * @param string $verb
     * @param $path
     * @return string
     */
    public function makeRequestWithSampleDataResponse( $verb = 'POST', $path, array $options)
    {
        $path = $this->_serverUrl  . '/' . $path;

        $response = $this->_requestClient->request($verb, $path, $options, $this->getSampleDataResponse());

        $responseText = (string)$response->getBody();

        return $responseText;
    }

    /**
     * @param $username
     * @return array
     */
    public function getWsseHeaderOptionsForBodyDataByUsername($username)
    {
        return [
                'X-WSSE' => $this->getHeaderStringByUsername($username),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
        ];
   }


    /**
     * Test if DELETE METHOD is working
     */
    public function testDelete()
    {
        /**
         * create an id by post a new item
         */
        $wsseDataHeaderOptions = $this->getWsseHeaderOptionsForBodyDataByUsername($this->_adminuser);

        $responseText = $this->makeRequestWithSampleDataResponse('POST', $this->_bundle, $wsseDataHeaderOptions);

        $responseAsObject = json_decode($responseText);

        $id = $responseAsObject->content->return->id;
        /**
         * delete the last created id
         */
        $uri = $this->_serverUrl  . '/' . $this->_bundle .'/' . $id;

        $authenticationHeader = $this->_requestClient->getWsseHeaderRequestOtionsByUsername($this->_adminuser);

        $response = $this->_requestClient->request("DELETE", $uri, $authenticationHeader);

        $responseText = (string)$response->getBody();

        $responseAsObject = json_decode($responseText);

        $this->assertEquals(200, $responseAsObject->status);

        $this->assertEquals('success', $responseAsObject->content->message);
    }

    /**
     * Test if deletion fails
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testDeleteFail()
    {
        $uri = $this->_serverUrl  . '/' . $this->_bundle .'/fakeid';

        $this->_requestClient->request("DELETE", $uri);

        $this->expectExceptionCode(404);
    }
}