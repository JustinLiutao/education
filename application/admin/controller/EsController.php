<?php
/**
 * Created by PhpStorm.
 * User: tao12.liu
 * Date: 2019/6/20
 * Time: 8:31
 */

namespace app\admin\controller;

use app\admin\model\TalkModel;
use think\Controller;
use Elasticsearch\ClientBuilder;
use app\admin\model\UserModel;

require '../vendor/autoload.php';

class EsController extends Controller
{
    private $client;

    public function __construct()
    {
        //初始化操作
        $params = array(
            '127.0.0.1:9200'
        );
        $this->client = ClientBuilder::create()->setHosts($params)->build();

        //$r = $this->delete_index('test'); exit;   //删除索引
        //$r = $this->create_index('test');  exit;  //1.创建索引
        //$r = $this->create_mappings('test_type', 'test');  exit;  //2.创建文档模板
        //$r = $this->get_mapping('test_type', 'test'); exit;
    }

    /*
     * 功能：生成索引，添加文档
     * return
     */
    public function addDoc()
    {
        $docs = [];
        $docs[] = ['id' => 1, 'name' => '小明', 'profile' => '我做的ui界面强无敌。', 'age' => 23];
        $docs[] = ['id' => 2, 'name' => '小张', 'profile' => '我的php代码无懈可击。', 'age' => 24];
        $docs[] = ['id' => 3, 'name' => '小王', 'profile' => 'C的生活，快乐每一天。', 'age' => 29];
        $docs[] = ['id' => 4, 'name' => '小赵', 'profile' => '就没有我做不出的前端页面。', 'age' => 26];
        $docs[] = ['id' => 5, 'name' => '小吴', 'profile' => 'php是最好的语言。', 'job' => 21];
        $docs[] = ['id' => 6, 'name' => '小翁', 'profile' => '别烦我，我正在敲bug呢！', 'age' => 25];
        $docs[] = ['id' => 7, 'name' => '小杨', 'profile' => '为所欲为，不行就删库跑路', 'age' => 27];

        foreach ($docs as $row) {
            $params = [
                'index' => 'tp5',
                'type' => 'test',
                'id' => 'test_' . $row['id'],
                'body' => [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'profile' => $row['profile'],
                    'age' => !empty($row['age']) ? $row['age'] : '',
                    'job' => !empty($row['job']) ? $row['job'] : '',
                ]
            ];

            $this->client->index($params);
        }

        return json(['msg' => 'success']);
    }

    /** 获取单条文档
     * return
     */
    public function getDoc()
    {
        $params = [
            'index' => 'tp5',
            'type' => 'test',
            'id' => 'test_1',
            //'client' => ['ignore' => '404']  //忽略404错误
        ];

        try {
            $res = $this->client->get($params);
            dump($res);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /** 更新文档
     * @param int $id
     * @param string $index_name
     * @param string $type_name
     * @return array
     */
    public function updateDoc()
    {
        $params = [
            'index' => 'tp5',
            'type' => 'test',
            'id' => 'test_7',
            'body' => [
                'doc' => [
                    'name' => '大王'
                ]
            ]
        ];

        $response = $this->client->update($params);
        dump($response);
    }

    /** 从索引中删除单条文档
     * return
     */
    public function delDoc()
    {
        $params = [
            'index' => 'tp5',
            'type' => 'test',
            'id' => 'test_7'
        ];

        try {
            $res = $this->client->delete($params);
            print_r($res);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /** 查询文档 (分页，排序，权重，过滤)
     * @param string $keywords
     * @param string $index_name
     * @param string $type_name
     * @param int $from
     * @param int $size
     * @return array
     */
    public function searchDoc()
    {
        $params = [
            'index' => 'tp5',
            'type' => 'test',
        ];

        #单个字段匹配 : match
        //$params['body']['query']['match']['profile'] = 'php';

        #多字段匹配 : multi_match[ 'query', 'fields', 'type' ]
        //$params['body']['query']['multi_match']['query'] = 'php';
        //$params['body']['query']['multi_match']['fields'] = ["name", "profile"];
        //$params['body']['query']['multi_match']['type'] = "most_fields"; // most_fields 多字段匹配度更高 best_fields  完全匹配占比更高

        #完全匹配( 感觉跟单子段匹配一样 )
        //$params['body']['query']['match_phrase']['profile'] = 'bug';

        #联合搜索  must,should,must_not
        //$params['body']['query']["bool"]['must_not']["match"]['age'] = "24";
        //$params['body']['query']["bool"]['must']["match"]['profile'] = "bug";

        $params['body'] = [
            'query' => [
                'bool' => [
                    'should' => [
                        ['match' => [
                            'profile' => [
                                'query' => 'php',
                                'boost' => 3, // 权重大
                            ]]],
                        ['match' => [
                            'age' => [
                                'query' => '24',
                                'boost' => 2,
                            ]]],
                    ],
                ],
            ],
            'sort' => ['age' => ['order' => 'desc']],
            'from' => 0,
            'size' => 5
        ];

        $response = $this->client->search($params);
        dump($response);
    }

    /** 创建索引
     * @param string $index_name
     * @return array|mixed|string
     */
    public function create_index($index_name = 'tp5')
    {
        // 只能创建一次
        $params = [
            'index' => $index_name,
            'body' => [
                'settings' => [
                    'number_of_shards' => 5,   //分片数
                    'number_of_replicas' => 0  //副本数
                ]
            ]
        ];

        try {
            return $this->client->indices()->create($params);
        } catch (\Elasticsearch\Common\Exceptions\BadRequest400Exception $e) {
            $msg = $e->getMessage();
            $msg = json_decode($msg, true);
            return $msg;
        }
    }

    /** 删除索引
     * @param string $index_name
     * @return array
     */
    public function delete_index($index_name = 'tp5')
    {
        $params = ['index' => $index_name];
        $response = $this->client->indices()->delete($params);
        dump($response);
    }

    // 判断文档存在
    public function exists_doc($id = 1, $index_name = 'test_ik', $type_name = 'users')
    {
        $params = [
            'index' => $index_name,
            'type' => $type_name,
            'id' => $id
        ];

        $response = $this->client->exists($params);
        return $response;
    }

    // 创建文档模板
    public function create_mappings($type_name = '', $index_name = '')
    {
        $params = [
            'index' => $index_name,
            'type' => $type_name,
            'body' => [
                $type_name => [
                    '_source' => [
                        'enabled' => true
                    ],
                    'properties' => [
                        'id' => [
                            'type' => 'integer', // 整型
                            'index' => 'not_analyzed',
                        ],
                        'name' => [
                            'type' => 'string', // 字符串型
                            'index' => 'analyzed', // 全文搜索
                            'analyzer' => 'ik_max_word'
                        ],
                        'profile' => [
                            'type' => 'string',
                            'index' => 'analyzed',
                            'analyzer' => 'ik_max_word'
                        ],
                        'age' => [
                            'type' => 'integer',
                        ],
                    ]
                ]
            ]
        ];

        $response = $this->client->indices()->putMapping($params);
        return $response;
    }

    // 查看映射
    public function get_mapping($type_name = '', $index_name = '')
    {
        $params = [
            'index' => $index_name,
            'type' => $type_name
        ];
        $response = $this->client->indices()->getMapping($params);
        return $response;
    }
}