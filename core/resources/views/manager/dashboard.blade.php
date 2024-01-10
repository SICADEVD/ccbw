@extends('manager.layouts.app')
@section('panel')
 
    <div class="row gy-4"> 
    <div class="col-xxl-4 col-sm-4">  
                        <div class="card box--shadow2 bg--white" id="producteur" style="min-height:230px;"> 
                        </div>
        </div>
        <div class="col-xxl-4 col-sm-4"> 
        <div class="card box--shadow2 bg--white" id="mapping" style="min-height:230px;"> 
         
        </div>
        </div>

        <div class="col-xxl-4 col-sm-4"> 
                    <div class="card box--shadow2 bg--white" id="formationmodule" style="min-height:230px;">  
                    
                    </div>
        </div>
        <div class="col-xxl-4 col-sm-4"> 
                    <div class="card box--shadow2 bg--white" id="producteurmodule" style="min-height:230px;"> 
                    </div>
        </div>
 


    </div><!-- row end-->

   
@endsection


@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap justify-content-end">
        <h3>{{ __(auth()->user()->cooperative->name) }}</h3>
    </div>
@endpush
@push('script')  
<?php
use Illuminate\Support\Str;
            foreach($genre as $data){
                $labels[] = utf8_encode(Str::remove("\r\n",utf8_decode($data->sexe)));
                $total[] = $data->nombre;
                $name = utf8_encode(Str::remove("\r\n",utf8_decode($data->sexe)));
                $value = $data->nombre;
                $donnees[] = "{ value: $value, name: '$name' }";
            }
            foreach($parcelle as $data){
                $labels[] = utf8_encode(Str::remove("\r\n",utf8_decode($data->typedeclaration)));
                $total[] = $data->nombre;
                $name = utf8_encode(Str::remove("\r\n",utf8_decode($data->typedeclaration ? $data->typedeclaration : 'Aucun')));
                $value = $data->nombre;
                $donnees2[] = "{ value: $value, name: '$name' }";
            }

            foreach($formation as $data){
                $labels3[] = utf8_encode(Str::remove("\r\n",utf8_decode($data->nom)));
                $total3[] = $data->nombre;
            }
            $i=0;
            
            foreach($modules as $data){
                $labels4[] = utf8_encode(Str::remove("\r\n",utf8_decode($modulenom[$i]->nom)));
                $total4[] = $data->nombre_producteurs;
                $i++;
            }
            ?>
<script type="text/javascript">
      // Initialize the echarts instance based on the prepared dom
      var myChart = echarts.init(document.getElementById('producteur'));

      // Specify the configuration items and data for the chart
      var option = {
  title: {
    text: 'Producteur par Genre',
    subtext: '',
    left: 'center'
  },
  tooltip: {
    trigger: 'item'
  },
  legend: {
    orient: 'horizontal',
    bottom: 'bottom'
  },
  series: [
    {
      name: '',
      type: 'pie',
      label: {
        formatter: '{d}',
        position: 'outside'
      },
      radius: '50%',
      data: [
        <?php echo implode(",",$donnees); ?>
      ],
      emphasis: {
        itemStyle: {
          shadowBlur: 10,
          shadowOffsetX: 0,
          shadowColor: 'rgba(0, 0, 0, 0.5)'
        }
      }
    }
  ]
};
// Display the chart using the configuration items and data just specified.
myChart.setOption(option);

 // Initialize the echarts instance based on the prepared dom
 var myChart2 = echarts.init(document.getElementById('mapping'));

// Specify the configuration items and data for the chart
var option2 = {
title: {
text: 'Mapping par Parcelle',
subtext: '',
left: 'center'
},
tooltip: {
trigger: 'item'
},
legend: {
orient: 'horizontal',
bottom: 'bottom'
},
series: [
{
name: '',
type: 'pie',
label: {
  formatter: '{d}',
  position: 'outside'
},
radius: '50%',
data: [
  <?php echo implode(",",$donnees2); ?>
],
emphasis: {
  itemStyle: {
    shadowBlur: 10,
    shadowOffsetX: 0,
    shadowColor: 'rgba(0, 0, 0, 0.5)'
  }
}
}
]
};
// Display the chart using the configuration items and data just specified.
myChart2.setOption(option2);

var myChart3 = echarts.init(document.getElementById('formationmodule'));
 
        // specify chart configuration item and data
        var option3 = {
            title: { 
                show: true,
                text: 'Formations par Module'
            },
            tooltip: {}, 
            legend: {
                data: [<?php echo "'".implode("','",$labels3)."'"; ?>]
            },
            xAxis: {
                data: [<?php echo "'".implode("','",$labels3)."'"; ?>]
            },
            yAxis: {},
            series: [{
                name: '',
                label: {
            show: true
            },
                type: 'bar',
                data: [<?php echo "'".implode("','",$total3)."'"; ?>]
            }]
        };

        // use configuration item and data specified to show chart
        myChart3.setOption(option3);


        // Display the chart using the configuration items and data just specified.
myChart2.setOption(option2);

var myChart4 = echarts.init(document.getElementById('producteurmodule'));
 
        // specify chart configuration item and data
        var option4 = {
            title: { 
                show: true,
                text: 'Producteurs formés par Module'
            },
            tooltip: {}, 
            legend: {
                data: [<?php echo "'".implode("','",$labels4)."'"; ?>]
            },
            xAxis: {
                type: 'value',
    boundaryGap: [0, 0.01]
            },
            yAxis: {
                type: 'category',
                data: [<?php echo "'".implode("','",$labels4)."'"; ?>]
            },
            series: [{
                name: '',
                label: {
            show: true
            },
                type: 'bar',
                data: [<?php echo "'".implode("','",$total4)."'"; ?>]
            }]
        };

        // use configuration item and data specified to show chart
        myChart4.setOption(option4);
    </script>
@endpush