<?php

namespace Websight\SearchDebugBundle\Lucene;

use Ivory\LuceneSearchBundle\Model\LuceneManager;

class DebuggingLuceneManager extends LuceneManager
{
    /**
     * @var array
     */
    protected $debuggableIndexes = [];

    /**
     * @var array
     */
    protected $debuggableIndexConfig = [];

    /**
     * @return array
     */
    public function getDebuggableIndexes()
    {
        return $this->debuggableIndexes;
    }

    /**
     * {@inheritdoc}
     */
    public function setIndex(
        $identifier,
        $path,
        $analyzer = self::DEFAULT_ANALYZER,
        $maxBufferedDocs = self::DEFAULT_MAX_BUFFERED_DOCS,
        $maxMergeDocs = self::DEFAULT_MAX_MERGE_DOCS,
        $mergeFactor = self::DEFAULT_MERGE_FACTOR,
        $permissions = self::DEFAULT_PERMISSIONS,
        $autoOptimized = self::DEFAULT_AUTO_OPTIMIZED,
        $queryParserEncoding = self::DEFAULT_QUERY_PARSER_ENCODING
    ) {
        parent::setIndex(
            $identifier,
            $path,
            $analyzer,
            $maxBufferedDocs,
            $maxMergeDocs,
            $mergeFactor,
            $permissions,
            $autoOptimized,
            $queryParserEncoding
        );
        $this->debuggableIndexConfig[$identifier] = [
            'path'                  => $path,
            'analyzer'              => $analyzer,
            'max_buffered_docs'     => $maxBufferedDocs,
            'max_merge_docs'        => $maxMergeDocs,
            'merge_factor'          => $mergeFactor,
            'permissions'           => $permissions,
            'auto_optimized'        => $autoOptimized,
            'query_parser_encoding' => $queryParserEncoding,
        ];
    }

    public function getIndex($identifier)
    {
        $index = parent::getIndex($identifier);

        $config = $this->debuggableIndexConfig[$identifier];

        $this->debuggableIndexes[$identifier] = new MeasurableIndex($index->getDirectory(), false);
        $this->debuggableIndexes[$identifier]->setMaxBufferedDocs($config['max_buffered_docs']);
        $this->debuggableIndexes[$identifier]->setMaxMergeDocs($config['max_merge_docs']);
        $this->debuggableIndexes[$identifier]->setMergeFactor($config['merge_factor']);

        return $this->debuggableIndexes[$identifier];
    }

    /**
     * @return array
     */
    public function getDebuggableIndexConfig()
    {
        return $this->debuggableIndexConfig;
    }
}
