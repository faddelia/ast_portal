<style scoped>
    .action-link {
        cursor: pointer;
    }

    .m-b-none {
        margin-bottom: 0;
    }
</style>

<template>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th><a id="sortby-date" class="action-nav" href="?sortby=date&sortdirection=desc">Date</a></th>
                    <th><a id="sortby-company" class="action-nav" href="?sortby=company&sortdirection=desc">Company</a></th>
                    <th><a id="sortby-name" class="action-nav" href="?sortby=name&sortdirection=desc">Name</a></th>
                    <th><a id="sortby-communication-type" class="action-nav" href="?sortby=communication-type&sortdirection=desc">Communication Type</a></th>
                    <th><a id="sortby-contact" class="action-nav" href="?sortby=contact&sortdirection=desc">Contact</a></th>
                    <th><a id="sortby-subject" class="action-nav" href="?sortby=subject&sortdirection=desc">Subject</a></th>
                    <th><a id="sortby-action" class="action-nav" href="?sortby=action&sortdirection=desc">Comment/Action Item</a></th>
                    <th>Archive</th>
                    <!-- check if admin?? -->
                        <th><a id="sortby-assigned-to" class="action-nav" href="?sortby=date&sortdirection=desc">Assigned To</a></th>
                    <!-- /check if admin?? -->
                </tr>
            </thead>
            <tbody v-if="actions.length > 0">
                <tr v-for="action in actions">
                    <td>
                        {{ action.string_date }}
                    </td>
                    <td>
                        {{ action.company_name }}
                    </td>
                    <td>
                        {{ action.name }}
                    </td>
                    <td>
                        {{ action.communication_type }}
                    </td>
                    <td>
                        {{ action.contact }}
                    </td>
                    <td>
                        {{ action.status }}
                    </td>
                    <td>
                        {{ action.action_item }}
                    </td>
                    <td>
                        <input type="checkbox" :id="'archive-' + action.id" class="archive" :name="'archive-' + action.id">
                    </td>
                    <td :id="'record-' + action.id" class="assigned-to">
                        {{ action.assigned_to }}
                    </td>
                </tr>
            </tbody>
        </table>
        <p id="add-action" style="text-align: center;">
          <button id="action-log-add" class="btn btn-sm btn-primary edit">Add Item</button>
          <button id="action-log-edit" class="btn btn-sm btn-danger edit">Edit Items</button>
        </p>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                actions: []
            }
        },
        methods: {
            getActionLogs(location) {

                var company = location.split("/");
                company = company[company.length - 1];

                axios.get('/action-log/' + company)
                     .then(response => {

                        //this.actions = response.data;                        
                        console.log(response.data);

                     })
                     .catch(error => {
                        console.log('error! ' + error);
                     });
            }
        },
        mounted() {
            this.getActionLogs(window.location.href);
        }
    }
</script>
