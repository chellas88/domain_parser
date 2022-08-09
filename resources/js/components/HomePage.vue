<template>
    <div class="container">
        <div class="row">
            <div class="col-8">
                <textarea class="form-control domains" v-model="domain"></textarea>
            </div>
            <div class="col-4 ltds">
                <div class="form-control align-items-center p-2">
                    <input type="checkbox" id="select_all" class="mx-2" @change="selectAll" ref="all"><label for="select_all" >Select All</label>
                    <div class="ltds-list">
                        <div v-for="(item, key) in ltds">
                            <input type="checkbox" :id="key" class="mx-2" v-model="selected[key]"><label :for="key">{{ key }}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 my-3">
                <button class="btn btn-secondary btn-sm mx-2" @click="search">Start</button>
                <button class="btn btn-secondary btn-sm mx-2">Reset</button>
            </div>

            <div class="col-12 my-3">
                <h5>Results <span ref="count">{{ finish }}/{{ count }}</span><span class="loader-block" ref="loader"><img class="loader" src="/loader.gif"> </span> </h5>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Domain</th>
                        <th scope="col">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="row in result" :id="row.domain">
                        <th scope="row"><a :href="'http://' + row.domain">{{ row.domain }}</a></th>
                        <td>{{ row.status }}</td>

                    </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: "HomePage",
    props: {
        ltds: {
            type: Object
        }
    },
    data() {
        return {
            ltds_list: [],
            selected: {},
            domain: '',
            result: [],
            count: 0,
            finish: 0
        }
    },
    beforeMount() {
        this.ltds_list = Object.keys(this.ltds)
        this.ltds_list.forEach(domain => {
            this.selected[domain] = false
        })
    },
    methods: {
        selectAll(event){
            if (this.$refs.all.checked) {
                for(var item in this.selected){
                    console.log(this.selected[item])
                    this.selected[item] = true
                }
            }
            else {
                for(var item in this.selected){
                    console.log(this.selected[item])
                    this.selected[item] = false
                }
            }
        },
        async search(){
            this.$refs.loader.style.display = 'inline'

            this.result = []
            this.count = 0
            for (let ltd in this.selected) {
                if (this.selected[ltd] === true) {
                    this.count++
                }
            }
            for (let ltd in this.selected){
                if (this.selected[ltd] === true){
                    let data = {
                        domain : this.domain + '.' + ltd
                    }
                    console.log(data)
                    await axios.post('/search', data)
                        .then(response => {
                            console.log(response)
                            this.result.push({
                                status : response.data[0].status,
                                domain : response.data[0].domain
                            })
                            this.finish++
                            if (this.count === this.finish) {
                                this.$refs.loader.style.display = 'none'
                                this.count = 0
                                this.finish = 0
                            }
                        })
                        .catch(error => {
                            console.log(error)
                        })
                }
            }

        }
    }
}
</script>

<style scoped>
.domains, .ltds {
    height: 300px;
    overflow: auto;
}
.loader {
    width: 40px;
    margin-left: 40px;

}
.loader-block{
    display: none;
}
</style>
