'use strict';

const { Contract } = require('fabric-contract-api');

class NutmegContract extends Contract {
    async addHarvest(ctx, harvestId, farmerId, location, date, quantity) {
        const harvest = {
            harvestId,
            farmerId,
            location,
            date,
            quantity,
            owner: farmerId,
            docType: 'harvest'
        };

        await ctx.stub.putState(harvestId, Buffer.from(JSON.stringify(harvest)));
        return JSON.stringify(harvest);
    }

    async transferOwnership(ctx, harvestId, newOwnerId) {
        const harvestBytes = await ctx.stub.getState(harvestId);
        if (!harvestBytes || harvestBytes.length === 0) {
            throw new Error(`Harvest ${harvestId} does not exist`);
        }

        const harvest = JSON.parse(harvestBytes.toString());
        harvest.owner = newOwnerId;

        await ctx.stub.putState(harvestId, Buffer.from(JSON.stringify(harvest)));
        return JSON.stringify(harvest);
    }

    async getTransactionHistory(ctx, harvestId) {
        let iterator = await ctx.stub.getHistoryForKey(harvestId);
        let result = [];
        let res = await iterator.next();

        while (!res.done) {
            if (res.value) {
                const tx = res.value;
                const record = JSON.parse(tx.value.toString('utf8'));
                result.push({ txId: tx.txId, record });
            }
            res = await iterator.next();
        }
        return JSON.stringify(result);
    }
}

module.exports = NutmegContract;