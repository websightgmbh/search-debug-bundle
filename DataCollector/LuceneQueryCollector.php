<?php

namespace Websight\SearchDebugBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Websight\SearchDebugBundle\Lucene\DebuggingLuceneManager;
use Websight\SearchDebugBundle\Lucene\MeasurableIndex;

class LuceneQueryCollector extends DataCollector
{
    /**
     * @var DebuggingLuceneManager
     */
    private $luceneManager;

    public function __construct(DebuggingLuceneManager $luceneManager)
    {
        $this->luceneManager = $luceneManager;
    }

    /**
     * Collects data for the given Request and Response.
     *
     * @param Request    $request   A Request instance
     * @param Response   $response  A Response instance
     * @param \Exception $exception An Exception instance
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['queries'] = [];
        $this->data['indexes'] = [];

        foreach ($this->luceneManager->getDebuggableIndexConfig() as $indexName => $indexConfig) {
            $this->data['indexes'][$indexName] = $indexConfig;
        }
        foreach ($this->luceneManager->getDebuggableIndexes() as $debuggableIndex) {
            /** @var MeasurableIndex $debuggableIndex */
            $this->data['queries'][] = $debuggableIndex->getQueries();
        }
    }

    /**
     * Returns the name of the collector.
     *
     * @return string The collector name
     */
    public function getName()
    {
        return 'websight.search_debug.lucene_query_collector';
    }

    public function getIndexes()
    {
        return $this->data['indexes'];
    }

    public function getQueries()
    {
        return $this->data['queries'];
    }

    public function getTotalQueries()
    {
        $queryCount = 0;
        foreach ($this->data['queries'] as $index) {
            $queryCount += count($index);
        }

        return $queryCount;
    }

    public function getTotalQueryTime()
    {
        $queryTime = 0;
        foreach ($this->data['queries'] as $index) {
            foreach ($index as $query) {
                $queryTime += $query['stopwatch']->getDuration();
            }
        }

        return $queryTime;
    }
}
