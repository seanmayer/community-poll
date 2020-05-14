<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->bearerToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiY2Y3ZjYwNGIzMjE4ZDczYmNiNDk3NjExMGI2YzIzNzBkMzFmOTY4MDg1Njc0MTM1YjU3NWNiZDk1ZDlmNjkwOGQ2NjJiMWY5M2M2OTE0Y2IiLCJpYXQiOjE1ODkzODU3MzEsIm5iZiI6MTU4OTM4NTczMSwiZXhwIjoxNjIwOTIxNzMxLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.ngzdkrpOcIgz33QuCUhaJYEu5rFoCYoAJyereXMPfLPGHo1XBtD_sewBnykK4e_HgOpcagX7pfHxhq5dSPgI9zJMJUhKOnCt3ORLrrm5ONGseZhZ9XN-I65NKnG99MKQjYb9Jr2lZcG-IYinNlII6WLWa4G8pBkCxGcnDJ4EQJ-kvdX2jLn24YYIL34Puljlz8PDzP8z7UcpMDx-QzmgaLgSgtDcwGCIMl5AnINyt120kENWC9UJAipU-GGi11ndO6i-Jq5K5BmJn6-h56HULzH3DxDjCWyGFWNKsMVNf5frVCGlYxTkBSsF-i5UMKo4vEVULjW8T6mcsRU7bTcXLj9hmPsbl5SRs36olOYNPXah0mN7hw80k70261i1ioddvB2YZuN83UConskimWgxgu0cWAj0jPO3HB6gCxaLniBi5EulzQxPZGmbILQNxLvgQ5z_fCVWk2Q5xh4i7XihtgHINzELdcBukoXPvz0Cvo7xEHx8ltcaMd51Yio-mxTMLHbrYmlt6wo0Ar_kZ8KVyl9FxFAUHBnAmqhByrYY_yXcQSc54RNLKknyqj21OWILamCPKH3rQH__UOWrogpqCCcE8Mdo0_eBXSZb_SHaPbelKwLvH3tdHca0_2QV7atw5SngKIJCuqwilkCeAx9SYc8oLS-RoVvxZyD6wrGlxAI";
    }

    /**
     * @Given I have the payload:
     */
    public function iHaveThePayload(PyStringNode $string)
    {
        $this->payload = $string;
    }

    /**
     * @When /^I request "(GET|PUT|POST|DELETE|PATCH) ([^"]*)"$/
     */
    public function iRequest($httpMethod, $argument1)
    {
        $client = new GuzzleHttp\Client();
        $this->response = $client->request(
            $httpMethod,
            'http://127.0.0.1:8000' . $argument1,
            [
                'body' => $this->payload,
                'headers' => [
                    "Authorization" => "Bearer {$this->bearerToken}",
                    "Content-Type" => "application/json",
                ],
            ]
        );
        $this->responseBody = $this->response->getBody(true);
    }

    /**
     * @Then /^I get a response$/
     */
    public function iGetAResponse()
    {
        if (empty($this->responseBody)) {
            throw new Exception('Did not get a response from the API');
        }
    }

    /**
     * @Given /^the response is JSON$/
     */
    public function theResponseIsJson()
    {
        $data = json_decode($this->responseBody);

        if (empty($data)) {
            throw new Exception("Response was not JSON\n" . $this->responseBody);
        }
    }

    /**
     * @Then the response contains :arg1 records
     */
    //public function theResponseContainsRecords($arg1)
    //{
    //    $data = json_decode($this->responseBody);
    //    $count = count($data);
    //    return ($count === $arg1);
    //}

        /**
     * @Then the response contains a question
     */
    public function theResponseContainsAQuestion()
    {
        $data = json_decode($this->responseBody);
        $question = $data[0];

        if(!property_exists($question, 'question')){
            throw new Exception('This is not a question');
        }
    }

    /**
     * @Then the question contains a title of :arg1
     */
    public function theQuestionContainsATitleOf($arg1)
    {
        $data = json_decode($this->responseBody);

        if($data->title == $arg1){

        }else{
            throw new Exception('The title does not match.');
        }
    }

}