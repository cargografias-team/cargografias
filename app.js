
var express = require('express');
var http = require('http');
var compression = require('compression');

var app = express();

// all environments
app.set('port', process.env.PORT || 3000);
app.use(compression({
  threshold : 0, // or whatever you want the lower threshold to be
  filter    : function(req, res) {
    if (req.url.indexOf('json') > 0){
      res.setHeader( "Content-Encoding", "gzip" );
    }
    
  }
}));
app.use(express.static(__dirname + '/web'));
app.disable('etag');
var server = http.createServer(app);
server.listen(app.get('port'), function(){
  console.log('Express server listening on port ' + app.get('port'));
});
