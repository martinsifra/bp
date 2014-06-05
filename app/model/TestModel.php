<?php

namespace App\Model;

use Nette\Caching\Cache;

class TestModel extends BaseModel
{
    /** @var \Kdyby\Doctrine\EntityDao */
    public $dao;

    /** @var \Nette\Caching\IStorage $cacheStorage */
    private $cacheStorage;
    

    public function __construct(\Kdyby\Doctrine\EntityDao $dao, \Nette\Caching\IStorage $cacheStorage)
    {
        $this->dao = $dao;
        $this->cacheStorage = $cacheStorage;
    }
   

    public function evaluate($tests, $records)
    {
        $result = [];
        $in = $tests; // Input tests stack
        $i = 0;
        
        // While input stack contains tests
        while ($test = array_pop($in)) {
            $i++;

            // Get test's parameters
            if (array_key_exists($test->slug, $result)) {
                continue; // Parameter is evaluated yet. 
            }
            
            if ($test->source) {
                $cache = new Cache($this->cacheStorage);

                // Load from cache or new parsing
                $toCache = $cache->load('test\\' . $test->id);
                if ($toCache === NULL) {
                    $parser = new \M\Parser();
                    $php = $parser->parse($test->source)->getPHP();
                    $toCache = [
                        'source' => $php,
                        'parameters' => $parser->getParameters()
                    ];
                    $cache->save('test\\' . $test->id, $toCache);
                }

                // Test has no parameters ->count else push params to stack
                $_parameters = $toCache['parameters'];
                
//                \Nette\Diagnostics\Debugger::barDump('WHILE2:' . $test->slug);
                
                if (empty($_parameters)) {
                    // Volam interpret
                    // Vysledek do result
                    $result[$test->slug] = 'Hopla';
                } else {
                    // Pushing parameters to input stack
                    $needToEval = 0;
                    $in[] = $test;
                    
                    foreach ($_parameters as $key => $param) {
                        
                        if (array_key_exists($key, $result)) {
                            continue; // Parameter is evaluated yet. 
                        } else {
                            
                           $needToEval++;
                           $foundTest = $this->getTest($key, $tests);

                           if ($foundTest === NULL) {
                               throw new \M\ParserException('Invalid parameter name \'' . $key .'\'.');
                           }
                           
                           $in[] = $foundTest;
                        }
                    }

                    // Create a Closure
                    $eval = function() use ($result, $toCache, $test) {
                        $_parameter = $result;
                        
                        if(($return = @eval($toCache['source'])) === FALSE){
                            $return = NULL;
                        }
                        
                        if (is_numeric($return)) {
                            $return = round($return, $test->decimals);
                        }
                        return $return;
                    };
                    
                    if ($needToEval == 0) {
                        $result[$test->slug] = [
                            'id' => $test->id,
                            'value' => $eval()
                        ];
//                        \Nette\Diagnostics\Debugger::barDump($result);
                    }
                }
            } else {
                $result[$test->slug] = [
                    'id' => $test->id,
                    'value' => $this->getRecordValue($test->slug, $records),
                ];
//                \Nette\Diagnostics\Debugger::barDump($result);
            }
            
            
            // Heh, how to prevent loop
            if ($i > 200) {
                break;
            }
        }
        
//        \Nette\Diagnostics\Debugger::barDump($in);
//        \Nette\Diagnostics\Debugger::barDump($result);
        return $result;
    }
    
    
    private function getTest($slug, $tests)
    {
        foreach($tests as $test) {
            if ($test->slug == $slug) {
                return $test;
            }
        }
        
        return NULL;
    }  
    
    private function getRecordValue($slug, $records)
    {
        foreach($records as $record) {
            if ($record->test->slug == $slug) {
                return $record->value;
            }
        }
        
        return NULL;
    }
    
}