<?php

use App\Entity\Event; 
use App\Entity\EventComment;
use App\Entity\Team;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\ResponseTextException;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements KernelAwareContext
{
    private $token;

    private $placeHolders = [];

    use KernelDictionary;

    /**
     * @var Response|null
     */
    private $response;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario  @database-clear
     */
    public function clearData()
    {
        $connection = $this->getContainer()->get('doctrine')->getManager()->getConnection();
        $schemaManager = $connection->getSchemaManager();
        $tables = $schemaManager->listTables();
        $query = '';

        foreach ($tables as $table) {
            $name = $table->getName();
            $query .= 'TRUNCATE ' . $name . ';';
        }
        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        $connection->executeQuery($query, [], []);
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * @Given I send :method request to :url
     * @Given I send :method request to :url with data:
     */
    public function iSendRequestWithDataTo($method, $url, PyStringNode $string = null)
    {
        $client = $this->getAbstractBrowserClient();
        $client->restart();

        $client->request($method, $url, [], [], [
            'CONTENT_TYPE'       => 'application/json',
            'HTTP_ACCEPT'        => 'application/json',
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
        ],
            $string ? (string)$string : null
        );
    }

    /**
     * @Given I am authenticated as :username with :password password
     */
    public function iAmAuthenticatedAs(string $username, string $password)
    {
        $request = Request::create(
            '/api/auth/login',
            Request::METHOD_POST,
            [], [], [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'username' => $username,
                'password' => $password,
            ])
        );
        $response = $this->getKernel()->handle($request);
        $content = json_decode($response->getContent(), true);

        if (!is_array($content)) {
            throw new InvalidArgumentException('invalid token response');
        }

        $this->token = $content['token'];
    }

    /**
     * @Given /^there is data in team table:$/
     * @And /^there is data in team table:$/
     */
    public function thereIsDataInTeamTable(TableNode $tableNode)
    {
        $this->storeTableNodeDataInDbTableUsingEntityClass(
            Team::class,
            $tableNode
        );
    }

    /**
     * @Given /^there is data in event table:$/
     * @And /^there is data in event table:$/
     */
    public function thereIsDataInEventTable(TableNode $tableNode)
    {
        $this->storeTableNodeDataInDbTableUsingEntityClass(
            Event::class,
            $tableNode
        );
    }

    /**
     * @Given /^there is data in event_comment table:$/
     * @And /^there is data in event_comment table:$/
     */
    public function thereIsDataInEventCommentTable(TableNode $tableNode)
    {
        $this->storeTableNodeDataInDbTableUsingEntityClass(
            EventComment::class,
            $tableNode
        );
    }

    private function storeTableNodeDataInDbTableUsingEntityClass(string $entityClass, TableNode $tableNode)
    {
        $entityManager = $this->getEntityManager();

        $tableName = $entityManager->getClassMetadata($entityClass)->getTableName();

        $connection = $entityManager->getConnection();
        foreach ($tableNode->getColumnsHash() as $dataRow) {
            $connection->insert($tableName, $dataRow);
        }
    }

    private function getEntityManager()
    {
        return $this->getKernel()->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @Given /^database is clean$/
     */
    public function databaseIsClean()
    {
        $this->clearData();
    }

    /**
     * @Given /^the response should contain array with (\d+) elements$/
     *
     * @throws ResponseTextException
     */
    public function theResponseShouldContainArrayWithElements(int $elementsCount)
    {
        $client = $this->getAbstractBrowserClient();
        $contentRaw = $client->getResponse()->getContent();
        $responseContent = json_decode($contentRaw, true);

        if (!is_array($responseContent) || $elementsCount !== count($responseContent)) {
            throw new ResponseTextException(
                'Response does not contain element defined in scenario. Response: '.$contentRaw,
                $this->getMink()->getSession()->getDriver()
            );
        }
    }

    private function getAbstractBrowserClient(): AbstractBrowser
    {
        return $this->getMink()->getSession()->getDriver()->getClient();
    }
}
