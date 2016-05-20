[文档](http://docs.phinx.org/en/latest/)

### 获取表前缀

    $preFix = $this->getAdapter()->getPrefix();
    $tableName = $preFix."user";

### 常用PHINX

    $table = $this->table('mission_task');
    $table
        ->addColumn('award', 'decimal',array(
            "comment"=>"奖励绿电金额",
            "precision"=>10,
            "scale"=>1,
            "null"=>false,
            "default"=>0.0
        ))
        ->addColumn('mid', 'integer',array(
            "comment"=>"任务id",
            "limit"=>11,
            "null"=>false
        ))
        ->addColumn('title', 'string',array(
            "limit"=>120,
            "null"=>true,
            "comment"=>"子任务描述",
        ))
        ->create();
        
        
# mysql

    use Phinx\Db\Adapter\MysqlAdapter;
    
    MysqlAdapter::INT_BIG       # -9223372036854775808 ~ 9223372036854775807 / 0 ~ 18446744073709551615, 8 个字节
    MysqlAdapter::INT_REGULAR   # -2147483648 ~ 2147483647 / 0 ~ 4294967295
    MysqlAdapter::INT_MEDIUM    # -8388608 ~ 8388607 / 0 ~ 16777215 ,  4 个字节
    MysqlAdapter::INT_SMALL     # -32768 ~ 32767 / 0 ~ 65535 , 2 个字节
    MysqlAdapter::INT_TINY	    # -128 ~ 127 / 0 ~ 255,1 字节