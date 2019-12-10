#!/bin/bash
#sudo geth --datadir data0 --networkid 999 init genesis.json
sudo geth --identity "TestNode" --rpc --rpcaddr "0.0.0.0" --rpccorsdomain "*" --rpcport "8545" --rpcapi "web3,eth,personal,net" --datadir data0 --port "30303" --allow-insecure-unlock --preload "minepending.js" console
