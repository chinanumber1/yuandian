/*

 */
;(function(win){
    function MapGrid(b,t){
        this.dom = document.querySelector(b);
        this.init(this.dom,t);
    }
    MapGrid.prototype = {
        init : function(b,t){
            mapPrive._init(b,t)
        }
    };

    var mapPrive = (function(){
        return{
            _html : function(){
                var html = '<div class="editmap_header">'+
                    '		<div class="editmap_title"></div>'+
                    '		<input type="text" id="editmap_id" class="editmap_id" placeholder="请输入关键字,选定后搜索"/>	'+
                    '		<div id="searchResultPanel" style="border:1px solid #C0C0C0;width:150px;height:auto; display:none;"></div>'+
                    '</div>'+
                    '<div class="editmap_mapAll" id="editmap_mapAll">'+
                    ' 		<div id="tip" style="display: none"></div>'+
                    '</div>'+
                    '<div id="baiduTip" style="display: none"></div>'+
                    '<div class="editmMap_btnAll">'+
                    '		<button class="editmMap_btnAll_ok" type="button">确认</button>'+
                    '		<button class="editmMap_btnAll_colse" type="button">取消</button>'+
                    '</div>';
                return html;
            },
            _init : function(b,t){
                this.dom = b;
                this._data(t );

            },
            _data : function(d){
                //gouldMap || baiduMap
                var parems = {
                    type : d.type || gouldMap,
                    callback : d.callback || function(){}
                }
                this._bind(parems);
            },
            _bind : function(d){
                var bom,ovel,self = this;
                bom = document.createElement('div');
                bom.className = 'editmap_map';
                bom.style.opacity=0;
                //ovel = document.createElement('div');
                //ovel.className = 'ovel';
                document.body.appendChild(bom);
                //document.body.appendChild(ovel);
                var map_title = bom.querySelector('.editmap_title');
                bom.innerHTML = self._html();
                var mapOk = bom.querySelector('.editmMap_btnAll_ok'),
                    mapColse = bom.querySelector('.editmMap_btnAll_colse');
                returnMap(d.type);
                var _self = this;

                //bom.querySelector('#baiduTip').style.display = 'block';
                setTimeout(function(){
                    baiduMap.createMap(bom.querySelector('#editmap_mapAll'),d);
                    console.log(5555);

                },500);



                // gouldMap.createMap()


            },
            _btnBInd : function(b,d){

            },
            /* [_center 居中]
             * @param  {[type]} b [bom节点]
             * @return {[type]}     [description]
             * @author [张 竹]
             */

        }
    })();

    win.MapGrid = MapGrid;
}(window));