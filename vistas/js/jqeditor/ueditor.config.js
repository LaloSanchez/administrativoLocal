(function () {

    var URL = window.UEDITOR_HOME_URL || getUEBasePath();
    
    window.UEDITOR_CONFIG = {
        UEDITOR_HOME_URL: URL
        , serverUrl: URL + "php/controller.php"
        , toolbars: [[
            'undo', 'redo', '|',
            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'blockquote', 'pasteplain', '|', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
            'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
            'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
            'indent', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
            'horizontal', 'date', 'time', '|',
            'print', 'searchreplace'
        ]]
        //,initialFrameWidth:1000
        //,initialFrameHeight:320
        ,initialFrameHeight:400
        ,enableAutoSave: false
        ,autoSyncData:false
        ,'fontfamily':[
            { label:'',name:'arial',val:'arial, helvetica,sans-serif'},
            { label:'',name:'arialBlack',val:'arial black,avant garde'},
            { label:'',name:'timesNewRoman',val:'times new roman'}
        ]
        ,maximumWords:100000
        ,wordCount: false 
        ,autoHeightEnabled:false
        ,scaleEnabled:false
        //,minFrameWidth:800
        //,minFrameHeight:220
        ,autoFloatEnabled:false
        ,wordCountMsg:''
        ,wordOverFlowMsg:''
    };

    function getUEBasePath(docUrl, confUrl) {

        return getBasePath(docUrl || self.document.URL || self.location.href, confUrl || getConfigFilePath());

    }

    function getConfigFilePath() {

        var configPath = document.getElementsByTagName('script');

        return configPath[ configPath.length - 1 ].src;

    }

    function getBasePath(docUrl, confUrl) {

        var basePath = confUrl;


        if (/^(\/|\\\\)/.test(confUrl)) {

            basePath = /^.+?\w(\/|\\\\)/.exec(docUrl)[0] + confUrl.replace(/^(\/|\\\\)/, '');

        } else if (!/^[a-z]+:/i.test(confUrl)) {

            docUrl = docUrl.split("#")[0].split("?")[0].replace(/[^\\\/]+$/, '');

            basePath = docUrl + "" + confUrl;

        }

        return optimizationPath(basePath);

    }

    function optimizationPath(path) {

        var protocol = /^[a-z]+:\/\//.exec(path)[ 0 ],
            tmp = null,
            res = [];

        path = path.replace(protocol, "").split("?")[0].split("#")[0];

        path = path.replace(/\\/g, '/').split(/\//);

        path[ path.length - 1 ] = "";

        while (path.length) {

            if (( tmp = path.shift() ) === "..") {
                res.pop();
            } else if (tmp !== ".") {
                res.push(tmp);
            }

        }

        return protocol + res.join("/");

    }

    window.UE = {
        getUEBasePath: getUEBasePath
    };

})();
