const { Gateway, Wallets } = require('fabric-network');
const path = require('path');
const fs = require('fs');

async function invokeContract(functionName, args) {
    const ccpPath = path.resolve(__dirname, '..', 'connection.json');
    const ccp = JSON.parse(fs.readFileSync(ccpPath, 'utf8'));

    const walletPath = path.join(process.cwd(), 'wallet');
    const wallet = await Wallets.newFileSystemWallet(walletPath);

    const gateway = new Gateway();
    try {
        await gateway.connect(ccp, {
            wallet,
            identity: 'appUser',
            discovery: { enabled: true, asLocalhost: true }
        });

        const network = await gateway.getNetwork('mychannel');
        const contract = network.getContract('nutmegCC');

        const result = await contract.submitTransaction(functionName, ...args);
        return result.toString();
    } finally {
        gateway.disconnect();
    }
}

module.exports = { invokeContract };
