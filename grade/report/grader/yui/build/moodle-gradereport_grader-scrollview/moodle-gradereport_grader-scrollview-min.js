YUI.add("moodle-gradereport_grader-scrollview",function(e,t){M.gradereport_grader=M.gradereport_grader||{},M.gradereport_grader.scrollview={SELECTORS:{CONTAINER:".gradeparent",STATIC:".gradeparent .right_scroller",GRADETABLE:"#user-grades"},container:null,init:function(){this.container=e.one(this.SELECTORS.CONTAINER);if(!this.container)return;var t=e.Node.create('<div class="right_scroller topscroll"><div class="topscrollcontent"></div></div>'),n=this.SELECTORS.CONTAINER;e.one(this.SELECTORS.STATIC)&&(n=this.SELECTORS.STATIC);var r=e.one(n).insert(t,"before");e.one(this.SELECTORS.STATIC)||(r=e.one(".topscroll"));var i=this;e.on("domready",function(){var t=e.one(i.SELECTORS.GRADETABLE).get("offsetWidth");e.one(".topscrollcontent").setStyle("width",t+"px")}),e.one(n).on("scroll",function(){r.set("scrollLeft",e.one(n).get("scrollLeft"))}),r.on("scroll",function(){e.one(n).set("scrollLeft",r.get("scrollLeft"))})}}},"@VERSION@",{requires:["base","node"]});