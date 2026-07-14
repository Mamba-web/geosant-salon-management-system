const months = [
    "Jan","Feb","Mar","Apr","May","Jun",
    "Jul","Aug","Sep","Oct","Nov","Dec"
];

// Revenue Chart
new Chart(document.getElementById("revenueChart"),{

    type:"line",

    data:{

        labels:months,

        datasets:[{

            label:"Revenue",

            data:revenueData,

            borderColor:"#7c3aed",

            backgroundColor:"rgba(124,58,237,.15)",

            borderWidth:3,

            fill:true,

            tension:.4

        }]

    },

    options:{

        responsive:true,

        plugins:{

            legend:{
                display:false
            }

        }

    }

});

// Appointment Chart

new Chart(document.getElementById("appointmentChart"),{

    type:"bar",

    data:{

        labels:months,

        datasets:[{

            label:"Appointments",

            data:appointmentData,

            backgroundColor:"#2D6BFF",

            borderRadius:8

        }]

    },

    options:{

        responsive:true,

        plugins:{

            legend:{
                display:false
            }

        }

    }

});