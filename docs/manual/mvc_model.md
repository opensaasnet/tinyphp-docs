Model 模型
====

* Model是属于MVC流程中的数据层处理。
  * Model负责处理所有的外部数据源，包括db, Nosql, api等；   
  * Model负责处理本地数据源Config，Cache。   
  * Model作为业务数据处理的模型层，只需和Controller交换数据，所有接口需要做到无状态模式，即不直接与控制器层的Request, Router, Dispatch, Bootstrap,Controller,Response等产生数据交换。   
  * Model基于数据层Data构建与外部数据源的数据交换。   
  
Model 通过  
    * Model基于数据层Data构建与外部数据源的数据交换。   
