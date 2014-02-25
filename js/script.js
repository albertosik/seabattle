
var field = [];
for(var i=0; i<10; i++)
{
    field[i] = [];
    for(var j=0; j<10; j++)
    {
        field[i][j] = 0;
    }
}
var shipTpl = 
{
        x:0,
        y:0,
        line:'',
        size:null
};

function ship(x,y,line,size)
{
        this.x=x;
        this.y=y;
        this.line=line;
        this.size=size;
        if(this.line==='up')
        {
            for(var i=this.y; i>this.y-size; i--)
            {
                $('#a'+i+this.x).css('background-color','#0000ff');
                field[i][this.x]=1;
            }
        }
        else if(this.line==='down')
        {
            for(var i=this.y; i<this.y+size; i++)
            {                
                $('#a'+i+this.x).css('background-color','#0000ff');
                field[i][this.x]=1;
            }
        }
        else if(this.line==='left')
        {
            for(var i=this.x; i>this.x-size; i--)
            {
                $('#a'+this.y+i).css('background-color','#0000ff');
                field[this.y][i]=1;
            }
        }
        else if(this.line==='right')
        {
            for(var i=this.x; i<this.x+size; i++)
            {
                $('#a'+this.y+i).css('background-color','#0000ff'); 
                field[this.y][i]=1;
            }
        }
}

ship.prototype=shipTpl;


$(function(){
    $('.cell').click(function(){
        doSend($(this).attr('id'));
    });
});

function rand(min,max)
{
    return Math.floor(Math.random()*10)%(max-min+1)+min;
}



function createLayout()
{
    var line = ['up','down','left','right'];
    
    ships.push(new ship(3,rand(3,6),line[3],4));
    
    ships.push(new ship(rand(0,1),rand(3,4),line[1],3));
    ships.push(new ship(rand(5,6),rand(8,9),line[2],3));
    
    ships.push(new ship(rand(2,3),1,line[0],2));
    ships.push(new ship(rand(5,6),0,line[1],2));
    ships.push(new ship(9,rand(3,6),line[2],2));
    
    ships.push(new ship(0,rand(0,1),line[0],1));
    ships.push(new ship(rand(8,9),rand(0,1),line[0],1));
    ships.push(new ship(rand(0,1),rand(8,9),line[0],1));
    ships.push(new ship(rand(8,9),rand(8,9),line[0],1));

    
}