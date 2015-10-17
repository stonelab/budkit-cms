/* ===================================================
 * budkit-graph.js v0.0.1
 * http://budkit.org/kb/graph
 * ===================================================
 * This class is loosely based on Jim Vallandingham network visualization
 * tutorial with d3 
 * http://flowingdata.com/2012/08/02/how-to-make-an-interactive-network-visualization/
 * 
 * Copyright 2012 The BudKit Team
 *
 * This source file is subject to version 3.01 of the GNU/GPL License 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/gpl.txt  If you did not receive a copy of
 * the GPL License and are unable to obtain it through the web, please
 * send a note to support@stonyhillshq.com so we can mail you a copy immediately.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */
!function($) {
    "use strict"
    var BKGraph = function(placeholder, data, options) {
        
        var _this  = this;
        
        this.options = $.extend({}, $.fn.bkgraph.defaults, options);
       
        this.curEdgesData = [];
        this.curNodesData = [];
        this.edgesByIndex = {};
        
        
        this.force = d3.layout.force();
        this.nodeColors = d3.scale.category20();
        this.graph = d3.select(placeholder).append("svg")
            .attr("viewBox", "0 0 " + this.options.width + " " + this.options.height )
            .attr("height", this.options.height)
            .attr("preserveAspectRatio", "xMidYMid meet");
        
        this.data  = this.init(data); 
         
        this.force.nodes( this.data.nodes )
        .links(this.data.edges)
        .size([this.options.width, this.options.height])
        .linkDistance(150)
        .charge(-200)
        .on("tick", function(){
            path.attr("d", function(d) {
                var dx = d.target.x - d.source.x,
                dy = d.target.y - d.source.y,
                dr = Math.sqrt(dx * dx + dy * dy);
                return "M" + d.source.x + "," + d.source.y + "A" + dr + "," + dr + " 0 0,1 " + d.target.x + "," + d.target.y;
            });
            node.attr("transform", function(d) {
                return "translate(" + d.x + "," + d.y + ")";
            })
        })
        .start();
            
        this.graph.append("svg:defs").selectAll("marker")
            .data(["end"])
            .enter().append("svg:marker")
            .attr("id", String)
            .attr("viewBox", "0 -5 10 10")
            .attr("refX", 30)
            .attr("refY", -1.8)
            .attr("markerWidth", 6)
            .attr("markerHeight", 6)
            .attr("orient", "auto")
            .append("svg:path")
            .attr("d", "M0,-5L10,0L0,5");
        
        var path = this.path = this.graph.append("svg:g").selectAll("path")
            .data(this.force.links())
            .enter().append("svg:path")
            .attr("class", "link")
            .attr("marker-end", "url(#end)");
    
        var node = this.node =  this.graph.append("g").selectAll(".node")
            .data(this.force.nodes())
            .enter()
            .append("svg:circle")
            .attr("r", 20)
            .style("fill", function(d){
                return _this.nodeColors(d.name);
            })
            //.call(this.force.drag);
            //.call(this.force.drag);

        this.node.append("text")
            .attr("x", 12)
            .attr("dy", ".35em")
            .text(function(d) {
                return d.name;
            });
  
    };
    //Graph Class
    BKGraph.prototype = {
        init : function(data) {
            var  nodes = {}, self = this;
      
            data.edges.forEach(function(link) {
                link.source = nodes[link.source] || 
                (nodes[link.source] = {
                    name: link.source
                });
                link.target = nodes[link.target] || 
                (nodes[link.target] = {
                    name: link.target
                });
            //link.value = +link.value;
            });      
            data.nodes = d3.values(nodes);
            
            return data;
        },
        strokeFor:function(d) {
            return d3.rgb(this.nodeColors(d.x)).darker().toString();
        }
    };
    //Plugin Defintion
    $.fn.bkgraph = function(data, option) {
        return this.each(function() {
            var $this = $(this)
            , options = typeof option == 'object' && option;
            //I probably should not be doing this but hey?
            $this.data('bkgraph', (new BKGraph(this, data, options)))
        });
    };
    $.fn.bkgraph.defaults = {
        width:960,
        height:500
    };
    $.fn.bkgraph.Constructor = BKGraph;
    /* GRAPH DATA-API
     * ============== */
    $(function() {
        $('[data-graph]').bkgraph({
            "nodes": [
            {
                "id": "one",      
                "match": 0.147889,
                "name": "Heart of Glass",
                "artist": "Blondie",
                "playcount": 2174351
            },
            {
                "id": "two",      
                "match": 0.147889,
                "name": "Heart of Glass",
                "artist": "Blondie",
                "playcount": 2174351
            },
            {
                "id": "three",      
                "match": 0.147889,
                "name": "Heart of Glass",
                "artist": "Blondie",
                "playcount": 2174351
            },
            {
                "id": "four",      
                "match": 0.147889,
                "name": "Heart of Glass",
                "artist": "Blondie",
                "playcount": 2174351
            }
            ],
            "edges": [
            {
                "source": "one",
                "target": "two"
            },
            {
                "source": "two",
                "target": "four"
            },
            {
                "source": "three",
                "target": "one"
            },
            {
                "source": "three",
                "target": "two"
            },
            {
                "source": "four",
                "target": "three"
            }
            ]
        }
        );
    })
}(window.jQuery);


