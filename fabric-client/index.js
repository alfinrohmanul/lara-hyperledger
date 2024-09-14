const express = require('express');
const bodyParser = require('body-parser');
const { Gateway, Wallets } = require('fabric-network');
const path = require('path');
const fs = require('fs');

const app = express();
app.use(bodyParser.json());

app.post('/invoke', async (req, res) => {
    try {
        const { functionName, args } = req.body;

        const ccpPath = path.resolve(__dirname, 'connection.json');
        const ccp = JSON.parse(fs.readFileSync(ccpPath, 'utf8'));

        const walletPath = path.join(process.cwd(), 'wallet');
        const wallet = await Wallets.newFileSystemWallet(walletPath);

        const identity = await wallet.get('appUser');
        if (!identity) {
            return res.status(401).send('An identity for the user "appUser" does not exist in the wallet');
        }

        const gateway = new Gateway();
        await gateway.connect(ccp, { wallet, identity: 'appUser', discovery: { enabled: true, asLocalhost: true } });

        const network = await gateway.getNetwork('mychannel');
        const contract = network.getContract('mycontract');

        const result = await contract.submitTransaction(functionName, ...args);
        await gateway.disconnect();

        res.send(result.toString());
    } catch (error) {
        res.status(500).send(`Failed to submit transaction: ${error}`);
    }
});

app.listen(3000, () => {
    console.log('Fabric client listening on port 3000');
});
