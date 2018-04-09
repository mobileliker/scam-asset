<p align="center">华南农业博物馆筹建办公室资产管理系统</p>


## 关于华南农业博物馆筹建办公室资产管理系统

华南农业博物馆筹建办公室资产管理系统是用于华南农业博物馆筹建办公室进行固定资产管理和藏品管理的系统


## License

华南农业博物馆筹建办公室资产管理系统基于Laravel，同样使用[MIT license](http://opensource.org/licenses/MIT).


## 更新

v2.0.3
- 新增了土壤管理中导出拍摄清单的段面标本的内容
- 新增了导出土壤Word文档的功能
- 新增了图片的标签字段
- 新增了土壤的冻土高度字段
- 新增了植物管理的拍摄清单功能新增了植物管理的拍摄清单功能
- 新增了首页固定资产、藏品入库统计功能
- 新增了农具管理的产地、保存地点、父分类属性
- 新增了农具图片的命令行工具（仅本地使用）
- 新增了藏品图片清理命令行工具
- 新增了附件管理模块
- 新增了对植物图片的导入支持
- 新增了林业资源管理模块和迁移工具
- 新增了固定资产的批量导入和批量导出报增单功能
- 修改了土壤管理的拍摄清单包括土壤采集记录
- 修改了农具管理的支持导入的EXCEL格式
- 修改了农具管理首页每页条数为15
- 修改了植物管理的导入以适应新的表格
- 优化了路由中id必须为数字
- 修复了导入图片时被删除的藏品依然会被查询到的错误
- 修复了固定资产管理编辑时数量属性的错误

### 计划
- 完善统计功能
- 升级到Laravel 5.5 LTS
- <del>土壤清单导出功能</del>
- 优化导入功能，减少冗余的日志记录

v2.0.2(dev) 2017/12/14
- 新增了土壤、动物管理功能(*)
- 新增了生成农具、岩石、土壤的拍摄清单功能
- 新增了岩石管理的“来源”字段
- 新增了岩石管理、土壤管理详情页的相似的“最后编辑时间”字段
- 新增了batch-user用户用于记录后台导入日志记录者
- 新增了土壤管理首页列表的段面标本和纸盒标本数量
- 新增了土壤管理的图片导入功能
- 新增了后台导入图片对大写后缀和jpg格式的图片的支持
- 新增了后台导出岩石文档的功能
- 完善了日志记录功能(*)
- 完善了图片导入功能导入错误的分类功能
- 完善了权限管理(*)
- 完善了登录页面的提示
- 修改了首页页面布局
- 修改了岩石管理、土壤管理的首页列表默认一页为15条
- 优化了岩石管理详情页没有图片的展示
- 优化了土壤管理详情页同时显示纸盒和段面标本图片
- 优化了图片导入功能的命令行提示
- 修复了农具管理数量的验证出错导致无法保存的问题
- 修复了版本更迭引发的编辑保存和删除功能无法使用的问题
- 修复了岩石管理新增或者编辑缺少尺寸和存放位置的问题
- 修复了岩石管理详情页中相似岩石列表最后编辑人的问题
- 修复了植物管理详情页缺少拉丁名和科属的问题
- 修复了图片导入提示语的问题
- 修复了首页的问题问题
- 修复了用户角色无法更改的问题

v2.0.1(dev)  2017/11/24
- 新增了农具、岩石、植物管理的基本功能

v2.0
- 将系统使用框架升级为Laravel 5.4，并启用了Vue+Eleme UI的新的前端页面
- 使用新的日志记录方式（SQL事件）

v1.0
- 基本完成了资产管理系统的模块


## 计划
- <del>使用Vue + Laravel 5.4 进行重构</del>
- <del>研究Laravel事件，完善日志功能</del>
- <del>更加详细的注释和MD</del>
- <del>加入Redis，优化数据库访问</del>
- 加入单元测试，完善测试
- 验证加入bail，抛弃list的用法
