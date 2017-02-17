<?php

namespace yiiunit\extensions\sphinx;

use yiiunit\extensions\sphinx\data\ar\ArticleIndex;
use yiiunit\extensions\sphinx\data\ar\ActiveRecord;

/**
 * @group sphinx
 */
class ActiveQueryTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        ActiveRecord::$db = $this->getConnection();
    }

    // Tests :

    public function testFacet()
    {
        $results = ArticleIndex::find()
            ->match('about')
            ->facets([
                'author_id',
            ])
            ->search();
        $this->assertNotEmpty($results['hits'], 'Unable to query with facet');
        $this->assertNotEmpty($results['facets']['author_id'], 'Unable to fill up facet');
        $this->assertTrue($results['hits'][0] instanceof ArticleIndex, 'Unable to populate results as AR object');
    }

    public function testWhereCompareIntAttr()
    {
        $results = ArticleIndex::find()
            ->select('id')
            ->match('about')
            ->where(['<', 'author_id', 5])
            ->limit(50)
            ->column();
        $this->assertNotEmpty($results);

        $results = ArticleIndex::find()
            ->select('id')
            ->match('about')
            ->where(['<', 'author_id', '5'])
            ->limit(50)
            ->column();
        $this->assertNotEmpty($results);
    }
}
