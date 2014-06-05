<?php

namespace App\Components\Athlete;

/**
 * 
 */
class TestGridControl extends \App\Components\Base\GridControl
{

    private $records;
    
    private $results;


    protected function createComponentGrido()
    {
        $data = [];
        
        foreach ($this->records as $record) {
            $data[$record->id] = [
                'created' => $record->created ? $record->created->format('j.n.Y') : '',
                'value' => $this->results[$record->id][$record->test->slug]['value']
            ];
        }
        
        
        
        $grid = new \Grido\Grid();
        $grid->model = new \Grido\DataSources\ArraySource($data);
        $grid->setFilterRenderType(\Grido\Components\Filters\Filter::RENDER_OUTER);

        
        ///// Grid settings /////
        $grid->setDefaultSort(array('created' => 'ASC'));
        $grid->setFilterRenderType(\Grido\Components\Filters\Filter::RENDER_INNER);
        
        //// Columns ////
//        $grid->addColumnText('id', 'ID');
        
        $grid->addColumnDate('created', 'Datum měření', 'j.n.Y')
                ->setSortable();
        
        $grid->addColumnText('value', 'Hodnota');

        return $grid;
    }
    
    public function setParams($results, $records)
    {
        $this->records = $records;
        $this->results = $results;
    }
    
}
