EmployeeTree = function (params) {
    return {
        subscribe: function () {
            var del = document.getElementsByClassName(params.del_class);
            for (var i = 0; i < del.length; i++) {
                del[i].addEventListener("click", function(){
                    if(confirm(params.del_text)) {
                        document.location.href = this.getAttribute('href');
                    }
                    return false;
                });
            }
            var n_del = document.getElementsByClassName(params.n_del_class);
            for (var k = 0; k < n_del.length; k++) {
                n_del[k].addEventListener("click", function(){
                    alert(params.n_del_text);
                    return false;
                });
            }
        }
    };
};
