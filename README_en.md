## AppSite
### Cross-Platform App/Site Development Engine

[简体中文](README.md) English
( Readme & Document not completed )

AppSite is a development engine based on object-oriental thinking. It includes a comprehensive server-end program, a back-end management,an API container and a website container.
There are several core components and basic models powerful to converge to implement any ideas.

AppSite can be used as a website, a back-end server program, an API server, a back-end management program or all of them in one.

<hr>

### Core Components
#### Basic Class: ASObject

AppSite uses basic objects to achieve some unified result processing because, under normal circumstances, the return of business code is divided into two basic situations: meeting expectations and not meeting expectations. ASResult encapsulates such result data, so that every step has Concise judgment form, and at the same time iteratively pass success/error data backward.
Each model class inherits the result attribute from the base class ASObject, and can feedback its own results to the requester.

```php
/**
 * Sample of result returning
 */

$checkUsername = \APS\User::common()->detail( filters:['username'=>'admin'] );

if( !$checkUsername->isSucceed() ){
    return $checkUsername;
}

\APS\User::common()->update(  params:['groupid'=>'900'], userid: $checkUsername->getContent()['userid'] );
```


#### Database controller: ASDB

ASDB is an operator used to request MySQL data, used to implement most commonly used database requests by an array.
The main common methods of ASDB are also further encapsulated in ASModel. For example, we need to query the list or details of articles, and we only need to use the corresponding method on the model instance.

```php
/**
 * Get data by ASDB methods
 */

// Get articles are featured  on page 1, size 15
$getListData = \APS\ASDB::shared()->get( ['uid','title','cover'], 'item_article', ['featured'=>1], 1, 15 );

// Get article details
$getDetailData = \APS\ASDB::shared()->get( ['*'], 'item_article', ['uid'=>'sampleUID'], 1, 1 );


/**
 * Get data by ASModel methods
 */

// Get articles are featured  on page 1, size 15
$getArticles = \APS\Article::common()->list( ['featured'=>1], 1, 15 );

// Get article details
$getArticleDetail = \APS\Article::common()->detail( 'sampleUID' );
```

#### Base Model: ASModel

ASModel is a base class that associates data with models.
In AppSite development, there is no need to pay too much attention to the basic data. In actual use, it is usually only necessary to record the corresponding field information in the model properties when the ASModel is inherited and it can be quickly called.
For example, in the Article class, there is the following definition: [Article.php](server/engine/service/Article.php)

```php
class Article extends \APS\ASModel{
    const table     = "item_article";
    const primaryid = "uid";

    const addFields = [
        'uid','categoryid','areaid',...'description','introduce',
        'viewtimes','sort','featured','status',
        'createtime','lasttime',
    ];
    ...
    const detailFields  = [...];
    ...
    const listFields  = [
        'uid','categoryid','areaid',...'createtime','lasttime'
    ];
    const filterFields  = [
        'uid','categoryid','areaid',...'createtime','lasttime'
    ];
    const depthStruct  = [
		'gallery'=>DBField_Json,
		'viewtimes'=>DBField_Int,
		'featured'=>DBField_Boolean,
		'createtime'=>DBField_TimeStamp,
    ];
    const tableStruct = [

		'uid'           =>['type'=>DBField_String,    'len'=>8,   'nullable'=>0,  'cmt'=>'索引ID' , 'idx'=>DBIndex_Unique ],
		'categoryid'    =>['type'=>DBField_String,    'len'=>8,   'nullable'=>1,  'cmt'=>'分类ID' , 'idx'=>DBIndex_Index ],
		'type'          =>['type'=>DBField_String,    'len'=>16,  'nullable'=>1,  'cmt'=>'类型 text,cover,video,gallery...' ],
		...
		'title'         =>['type'=>DBField_String,    'len'=>32,  'nullable'=>0,  'cmt'=>'名称名' ,  'idx'=>DBIndex_FullText ],
		'cover'         =>['type'=>DBField_String,    'len'=>255, 'nullable'=>1,  'cmt'=>'封面' ],
		'gallery'       =>['type'=>DBField_Json,      'len'=>-1,  'nullable'=>1,  'cmt'=>'详情介绍' ],
		...
		'description'   =>['type'=>DBField_String,    'len'=>255, 'nullable'=>1,  'cmt'=>'描述' ,   'idx'=>DBIndex_FullText ],
		'introduce'     =>['type'=>DBField_RichText,  'len'=>-1,  'nullable'=>1,  'cmt'=>'详情介绍' ],
		...
		'viewtimes'     =>['type'=>DBField_Int,       'len'=>13,  'nullable'=>0,  'cmt'=>'播放次数',  'dft'=>0,       ],
		'status'        =>['type'=>DBField_String,    'len'=>12,  'nullable'=>0,  'cmt'=>'状态',    'dft'=>'enabled',       ],
		...
		'createtime'    =>['type'=>DBField_TimeStamp,'len'=>13, 'nullable'=>0,  'cmt'=>'创建时间',           'idx'=>DBIndex_Index, ],
		'featured'      =>['type'=>DBField_Boolean,  'len'=>1,  'nullable'=>0,  'cmt'=>'置顶',  'dft'=>0,    'idx'=>DBIndex_Index, ],
    ];
}
```

When Article uses the inherited method list(), it will automatically use the `table`, `primaryid`, and `listFields` properties to query list data.

##### filterFields
Used to set the check fields allowed during the query (usually the indexed field, otherwise it will reduce the query efficiency)

##### depthStruct
Used to convert the string data from the database into the corresponding data type when extracting data

<hr>

### Semantic programming (coding style)

AppSite pursues semantic programming, that is, the code itself has high language characteristics, streamlined and easy to read.
For example, in the interface file of registered users, we use the following implementation:

```php
$scope   = $this->params["scope"] ?? 'common';

$registUser = User::common()->add($this->params);

if( $registUser->isSucceed() ){

    $user = new User($registUser->getContent(),null,$scope);
    return $user->access->authorize();
}
return $registUser;
```


<hr>

#### Nginx Rewrite:
( Route support for website & management )


```

# 图片裁切服务 Image Crop Service
location ~ \.(jpg|jpeg|png|webp)!(.*)$ {

    rewrite ^(.*)\.(jpg|jpeg|png|webp)!(.*)$ $1/crop/$3.$2;

    if ( -e $request_filename ) {
        break;
    }

    rewrite ^(.*)/crop/(.*)\.(.*)$ $1&ext=.$3&file=$1.$3&method=$2;
    rewrite ^(/website.*)*$ /api/common/imageCrop$1;
}

location /favicon.ico {break;}
location /website/static { break; }
location /website/theme { break; }
location /manager/theme { break; }
location /api {
    rewrite ^(/api.*)*$ /api/?path=$1 break;
}
location /tester {
    rewrite ^(/tester.*)*$ /tester/?path=$1 break;
}
location /manager {
    rewrite ^(/manager.*)*$ /manager/?path=$1 break;
}
location /website {
    rewrite ^(/website.*)*$ /website/?path=$1 break;
}
location / {
    rewrite ^(/.*)*$ /website/?path=$1 break;
}
```
If you want to hide your management system pages, modify the rootPath setting and rewrite like follow config.

```
location /manager321 {
    rewrite ^(/manager321.*)*$ /manager/?path=$1 break;
}
location /manager {
    rewrite ^(/manager.*)*$ / break;
}
```
