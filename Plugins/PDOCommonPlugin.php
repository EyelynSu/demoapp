<?php
#-------------------------------------------------------------------------------
# Copyright 2019 NAVER Corp
# 
# Licensed under the Apache License, Version 2.0 (the "License"); you may not
# use this file except in compliance with the License.  You may obtain a copy
# of the License at
# 
#   http://www.apache.org/licenses/LICENSE-2.0
# 
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
# WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.  See the
# License for the specific language governing permissions and limitations under
# the License.
#-------------------------------------------------------------------------------


namespace Plugins;
use Plugins\Candy;
use CDbDataReader;
///@hook:CDbConnection::createCommand
///@hook:CDbCommand::query CDbCommand::execute
class PDOCommonPlugin extends Candy
{
    function onBefore()
    {
//        echo $this->apId;
        pinpoint_add_clue("stp",MYSQL);
        if(strpos($this->apId, "createCommand")){
            pinpoint_add_clues(PHP_ARGS, $this->args[0]);
            pinpoint_add_clue("dst",$this->get_host($this->who->connectionString));
        }else{
            pinpoint_add_clue("dst",$this->get_host($this->who->getConnection()->connectionString));
        }
        }
    function onEnd(&$ret)
    {
//        pinpoint_add_clues(PHP_RETURN,print_r($ret,true));
    }

    function onException($e)
    {
        pinpoint_add_clue("EXP",$e->getMessage());
    }

    function get_host($dsn){
        $dsn = explode(':', $dsn);
        $temp = explode(';', $dsn[1]);
        $host = [];
        foreach ($temp as $h){
            $h = explode('=', $h);
            $host[] = $h[1];
        }
        $host = implode(":", $host);
        return $host;
    }
}
