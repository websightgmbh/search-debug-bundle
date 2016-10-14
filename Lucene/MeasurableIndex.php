<?php

namespace Websight\SearchDebugBundle\Lucene;

use Symfony\Component\Stopwatch\Stopwatch;
use ZendSearch\Lucene\Analysis\Analyzer\Analyzer;
use ZendSearch\Lucene\Index;

class MeasurableIndex extends Index
{
    /**
     * @var array
     */
    protected $metrics = [];

    /**
     * @var array
     */
    protected $queries = [];

    /** {@inheritdoc} */
    public function find($query)
    {
        $queryMetric = ['query' => $query];

        $stopwatch = new Stopwatch();
        $stopwatch->start('query');
        $queryMetric['result'] = parent::find($query);
        $stopwatch->stop('query');
        $queryMetric['stopwatch'] = $stopwatch->getEvent('query');

        $this->queries[] = $queryMetric;

        return $queryMetric['result'];
    }

    /**
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }

    public function __destruct()
    {
        // do nothing
    }
}
