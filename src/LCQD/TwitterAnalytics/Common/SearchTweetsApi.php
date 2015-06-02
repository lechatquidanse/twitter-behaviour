<?php

namespace LCQD\TwitterAnalytics\Common;

trait SearchTweetsApi
{
    protected $stUrl = 'search/tweets.json';
    protected $stQuery = array();
    protected $stParameters = array();

    public function addStParameterCount($count)
    {
        $this->stParameters['count'] = $count;

        return $this;
    }

    public function addStQueryFrom($from)
    {
        $this->stQuery[] = sprintf('from:%s', $from);

        return $this;
    }

    public function stringifyStQuery()
    {
        return implode('+', $this->stQuery);
    }

    protected function addStQueryInParameters()
    {
        $this->stParameters['q'] = $this->stringifyStQuery();
        
        return $this;
    }

    public function generateStOptions()
    {
        $this->addStQueryInParameters();

        return array('query' => $this->stParameters);
    }

    public function getStUrl()
    {
        return $this->stUrl;
    }

    public function setStUrl($stUrl)
    {
        $this->stUrl = $stUrl;
        
        return $this;
    }

    public function getStQuery()
    {
        return $this->stQuery;
    }

    public function setStQuery(array $stQuery)
    {
        $this->stQuery = $stQuery;
        
        return $this;
    }

    public function getStParameters()
    {
        return $this->stParameters;
    }

    public function setStParameters(array $stParameters)
    {
        $this->stParameters = $stParameters;
        
        return $this;
    }
}
