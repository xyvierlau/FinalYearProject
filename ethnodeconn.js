
function setWeb3(_acc, Cont_Add){

	web3 = new Web3(new Web3.providers.HttpProvider("http://10.0.93.208:8545"));
    web3.personal.unlockAccount(_acc,"1234pass", 15000);
    web3.isConnected();
    web3.eth.defaultAccount = _acc;
    var QueueSystem = web3.eth.contract([{"constant":false,"inputs":[{"name":"_queueNum","type":"uint256"}],"name":"cancelQueue","outputs":[{"name":"","type":"uint256"},{"name":"","type":"uint8"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_branchID","type":"string"},{"name":"_userID","type":"string"},{"name":"_userName","type":"string"},{"name":"_serviceType","type":"string"}],"name":"createQueue","outputs":[{"name":"","type":"uint256"},{"name":"","type":"string"},{"name":"","type":"string"},{"name":"","type":"string"},{"name":"","type":"string"},{"name":"","type":"uint8"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_serviceInfo","type":"string"}],"name":"finishQueue","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[],"name":"newDay","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[],"name":"nextQueue","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"getCurrentQueue","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"_qNum","type":"uint256"}],"name":"getQueueInfo","outputs":[{"name":"","type":"uint256"},{"name":"","type":"uint8"},{"name":"","type":"string"},{"name":"","type":"string"},{"name":"","type":"string"},{"name":"","type":"string"},{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"getTotalQueue","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"","type":"uint256"}],"name":"queueInfo","outputs":[{"name":"queueNum","type":"uint256"},{"name":"status","type":"uint8"},{"name":"branch","type":"string"},{"name":"userID","type":"string"},{"name":"userName","type":"string"},{"name":"serviceType","type":"string"},{"name":"serviceInfo","type":"string"}],"payable":false,"stateMutability":"view","type":"function"}]);
    qs = QueueSystem.at(Cont_Add);
}

function getTQ(){
    TotalQs = qs.getTotalQueue();
    TotalQa = TotalQs.c[0].toString();
    return TotalQa;
}

function getCQ(){
	CurrQs = qs.getCurrentQueue();
	CurrQa = CurrQs.c[0].toString();
	return CurrQa;
}

function waitTxn(txnid){
var repeat =true;
while(repeat)
{
try{
    qs._eth.getTransactionReceipt(txnid).blockNumber;
    repeat=false
}catch(e){
    repeat=true;
}
}
}
