import { Component, OnInit, AfterViewInit, ElementRef, ViewChild } from '@angular/core';
import * as L from 'leaflet';
import * as esri from 'esri-leaflet';
import * as geocoding from 'esri-leaflet-geocoder';
import { TerresService } from '../services/terres.service';
import { BsModalService } from 'ngx-bootstrap/modal';
import { NgModel, NgForm } from '@angular/forms';
import { ReserveSettings, TerreNyanga, ProvinceInoccuper } from '../data/reserve-settings';


@Component({
  selector: 'app-map',
  templateUrl: './map.component.html',
  styleUrls: ['./map.component.css']
})
export class MapComponent implements AfterViewInit {

  private map;
  private geoJson = L.geoJSON();
  @ViewChild('template') domModal : ElementRef;
  @ViewChild('templateTerreNyanga') modalTerreNyanga : ElementRef;
  @ViewChild('templateProvinceInoccuper') modalProvinceInoccuper : ElementRef;
  @ViewChild('templateTauxOccupationReserves') modalTauxOccupationReserves : ElementRef;


  coordonneeType = 'Point';

  addCoordinate = false;

  tauxOccupationReserve : number;

  private point;

  zoomLevel = 7;

  private static coffee_shops = {
    type: "FeatureCollection",
    features: [],
  };

  private static reserves = {
    type: "FeatureCollection",
    features: [],
  }

  TerreNyanga : TerreNyanga = 
  {
    code : null,
    nom : null,
    superficie : null

  }

  ProvinceInoccuper : ProvinceInoccuper = 
  {
    code : null,
    superficie : null,
    nom : null
  }

  public smallIcon = new L.Icon({
    iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-icon.png',
    iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-icon-2x.png',
    iconSize:    [35, 81],
    iconAnchor:  [12, 41],
    popupAnchor: [1, -34],
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
    shadowSize:  [41, 41]
  });

  reserveSettings : ReserveSettings = {

    name : null,
    type_geometry : null,
    longitude : null,
    latitude : null
  };

  listOfCoordinates = ''; // Pour stocker la liste des coordonnées sous forme de chaine de caractère
  
  listTerreNyanga : TerreNyanga[] = [];
  listProvinceInoccuper : ProvinceInoccuper[] = [];
  //coordinates contient la liste des coordonnées saisie par l'utilisateur
  coordinates = {
    lat : [],
    long : []
  }

  coffee: any; // For storing returned data by webservice
  terresOccuperNyanga : any; // Contient les terres de nyanga venant du webservice
  provinceInoccuper : any;
  tauxReserve : any;
  reserve: any; 
  nombreReserve = 0;
  nombreProvince = 0;
 
  // Injection des dépendances au composants
  constructor(private terresService : TerresService, public mod : BsModalService) { }

  // Ouvre le modal(formulaire)
  openModal(){
    this.mod.show(this.domModal);
 }

 closeModal() {
   this.mod.hide(1);
 }

 // fermeture modal (formulaire)

 // Debut functions d'ouverture et fermeture des requêtes topologiques
 openModalTerreNyanga(){
  this.mod.show(this.modalTerreNyanga);
}

closeModalTerreNyanga() {
 this.mod.hide(1);
}

openModalProvinceInoccuper(){
  this.mod.show(this.modalProvinceInoccuper);
}

closeModalProvinceInoccuper() {
 this.mod.hide(1);
}

openModalTauxOccupationReserves(){
  this.mod.show(this.modalTauxOccupationReserves);
}

closeModalTauxOccupationReserves() {
 this.mod.hide(1);
}

 // Fin functions d'ouverture et fermeture des requêtes topologiques


 onBlur(nameField : NgModel)
  {
    console.log("field status : ", nameField.valid);
  }

  ngAfterViewInit(): void {
    this.initMap();
    
  }

  onSubmit(form : NgForm)
  {
    
    if(form.valid)
    {
      this.zoomLevel = 8;
      this.map.setZoom(this.zoomLevel); // On zoom sur le nouveau point ajouter
      this.closeModal(); // Fermeture du formulaire modal
      // Ici nous dessinons la geomettry en fonction du choix de l'utilisateur
      switch(this.coordonneeType)
      {
        case 'Point' : 
        this.point = new L.Marker([this.reserveSettings.latitude, this.reserveSettings.longitude],
          {
            icon : this.smallIcon
            
          }).addTo(this.map);
          break;
        case 'Polygone' :
          
          let latlng = [];
          for(let i = 0; i < this.coordinates.lat.length; i++)
          {
              latlng.push([this.coordinates.lat[i], this.coordinates.long[i]]);
          }
          let polygon = L.polygon(latlng, {color: 'black'}).addTo(this.map);
          //this.map.fitBounds(polygon.getBounds());
          break;

        case 'Rectangle' :
          let latlngRectangle = [];
          for(let i = 0; i < this.coordinates.lat.length; i++)
          {
              latlngRectangle.push([this.coordinates.lat[i], this.coordinates.long[i]]);
          }
          let rectangle = L.rectangle(latlngRectangle, {color: 'black', weight: 2}).addTo(this.map);
          break;
        case 'Ligne' :
          let latlngPolyline = [];
          for(let i = 0; i < this.coordinates.lat.length; i++)
          {
              latlngPolyline.push([this.coordinates.lat[i], this.coordinates.long[i]]);
          }
          let polyline = L.polyline(latlngPolyline, {color: 'red'}).addTo(this.map);
          break;
      }
        
    }
  }

  // Cette fonction nous permet d'ajouter un ensemble de point

  onAddCoordinates()
  {
       this.addCoordinate = true; // pour afficher le panier

       this.coordinates.lat.push(this.reserveSettings.latitude);
       this.coordinates.long.push(this.reserveSettings.longitude);

       this.listOfCoordinates = ''; // Vide la liste des coordonnées
       
       // Construction du contenu du panier
       for (let i = 0; i < this.coordinates.lat.length; i++)
       {
         this.listOfCoordinates += '[lat : ' + this.coordinates.lat[i] + '] : [longitude : ' + this.coordinates.long[i] + ']\n';
       }
       
       this.emptyLatLng(); // On vide les champs latitude et longitude du formulaire
  }
   // Fonction pour vider La latitude et longitude du formulaire
  emptyLatLng()
  {
    this.reserveSettings.latitude = 0.0;
    this.reserveSettings.longitude = 0.0;

  }

  initMap()
  {
    
    /*
    const campusIAI = {
      lat : 42.37384 ,
      long : -71.12138 
    }
    */

   const campusIAI = {
    lat : -0.39550467153200675 ,
    long : 11.77734375 
   }
  
    let mbAttr = 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
    '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
    'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>';
    let mbUrl = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';

    let grayscale   = L.tileLayer(mbUrl, {id: 'mapbox/light-v9', tileSize: 512,  minZoom: 7, zoomOffset: -1, attribution: mbAttr});
    let streets  = L.tileLayer(mbUrl, {id: 'mapbox/streets-v11', tileSize: 512,  minZoom: 7, zoomOffset: -1, attribution: mbAttr});
    let googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
            maxZoom: 50,
            minZoom: 7,
            subdomains:['mt0','mt1','mt2','mt3']
        });

    if(this.map == null)
    {
      this.map = L.map('map', {
        center : [campusIAI.lat, campusIAI.long],
        zoom : this.zoomLevel,
        layers: [streets]
      });

    }

    // Debut prise en compte du formulaire
          
    
    // Fin prise en compte du formulaire

    let baseLayers = {
      "Grayscale": grayscale,
      "Streets": streets,
      "Satellite": googleSat
    };

    const controlLayers = L.control.layers(baseLayers, null,{collapsed:false});
    controlLayers.addTo(this.map);
    
   let legend = L.control.layers(null, null , {position: 'bottomright', collapsed : false});

      
   /* 
    let searchControl = geocoding.geosearch().addTo(this.map);

    let results = L.layerGroup().addTo(this.map);

    searchControl.on('results',(data: any) =>
    {
      results.clearLayers();

      for (let i = data.results.length - 1; i >= 0; i--) {
        results.addLayer(L.marker(data.results[i].latlng, 
          {
            icon : this.smallIcon,
            
          }));
    }
    }); */

    // Communication avec le service
    
    // Zone de collecte terres occupées dans la province de NYANGA
       
    this.terresService.getTerreByProvince().subscribe(
      resultat => 
      {
         this.terresOccuperNyanga = resultat.data;
         if(this.terresOccuperNyanga.length > 0)
         {
            for(let i = 0; i < this.terresOccuperNyanga.length; i++)
            {
             
             // Chargement de la liste des terres de Nyanga
              
             this.listTerreNyanga.push({
              "code"  : this.terresOccuperNyanga[i].CODE,
              "nom" : this.terresOccuperNyanga[i].NOM,
 
              "superficie" : this.terresOccuperNyanga[i].AREA
             });
            }
              
         }
      }  
   );

    // Fin zone collecte terres occupées dans la province de NYANGA 

    // Debut zone des provinces inoccupées
     
    this.terresService.getProvinceInoccuper().subscribe(
      resultat => 
      {
         this.provinceInoccuper = resultat.data;
         if(this.provinceInoccuper.length > 0)
         {
            for(let i = 0; i < this.provinceInoccuper.length; i++)
            {
             // Chargement de la liste des terres de Nyanga
              
             this.listProvinceInoccuper.push({
               // Création de l'objet Nyanga
             "code"  : this.provinceInoccuper[i].CODE,
             "nom" : this.provinceInoccuper[i].PROVINCE,

             "superficie" : this.provinceInoccuper[i].Surf_Km2

             });
            }
            
         }
      }  
   );
    // Fin zone des provinces inoccupées
     
    // Debut zone du taux d'occupation des reserves 
      this.terresService.getTauxOccupationReserve().subscribe(
          resultat => 
          {
            this.tauxReserve = resultat.data;
            
            for(let i = 0; i < 1; i++)
            {
              this.tauxOccupationReserve = this.tauxReserve[i].totalt;
            }
          }
      );
    // Fin zone du taux d'occupation des reserves 


    // Zone de collecte des provinces 
    this.terresService.getCoffeeShops().subscribe(

      // Successful data received

      coffee_shops => {
     //   console.log('Resulting data : ' + coffee_shops.data.length);
        
        this.coffee = coffee_shops.data;
        this.nombreProvince = coffee_shops.data.length;

     //   console.log('coffee 1 : ' + this.coffee[0].CODE);

        let i = 0;

        // Construction de notre objet geojson

        for (i = 0; i < coffee_shops.data.length; i++) 
        {
          MapComponent.coffee_shops.features.push({
            "type": "Feature",
            "geometry": {
              "type":JSON.parse(this.coffee[i].geojson).type,
              "coordinates":JSON.parse(this.coffee[i].geojson).coordinates
            },
            "properties": {
              "code": this.coffee[i].CODE,
              "nom": this.coffee[i].PROVINCE,
              "surface" : this.coffee[i].Surf_Km2,
              "perimetre" : this.coffee[i].PérimètreKm,
              "chef_lieu" : this.coffee[i].ChefLieu_Prov,
              "population" : this.coffee[i].POP93,
              "densite" : this.coffee[i].Dens_H_Km2,
              "pop_urbaine" : this.coffee[i].Pop_Urb,
              "pop_rural" : this.coffee[i].Pop_Rur,
              
            }
          });
        }

        // Fin construction objet geojson

     //   console.log("geoJson Data : " + JSON.stringify(MapComponent.coffee_shops));

        // geojson marker options
        let geojsonMarkerOptions = {
          radius: 8,
          fillColor: "#1cd1a1",
          color: "#ff6b6b",
          weight: 1,
          opacity: 1,
          fillOpacity: 0.8
        };
      
      // Conversion format json en form de chaine de caractère
      let coffee_shops_map = JSON.stringify(MapComponent.coffee_shops);

      const coffee_map = JSON.parse(coffee_shops_map); // Conversion objet en json

       const coffee_maps =  L.geoJSON(coffee_map, {
          pointToLayer: function (feature, latlng) {
            return L.circleMarker(latlng, geojsonMarkerOptions);
        },
          style: function style2(feature) {
            return {
                fillColor: getColor2(feature.properties.code),
                weight: 2,
                opacity: 1,
                color: 'white',
                dashArray: '3',
                fillOpacity: 0.8
            };
          },
        
          onEachFeature: this.onEachFeature
        });//.addTo(this.map);

        //jsonLayer.addData(JSON.parse(coffee_shops_map));

        this.map.addLayer(coffee_maps); // Add the geoJson layer to our map so it can be checked by default on control layer
        controlLayers.addOverlay(coffee_maps, "Les Provinces ( " + this.nombreProvince + ")");
        
      },

      error => {
        return this.onHttpError(error);
      }
    );

    // Fin zone collecte des provinces


    // Zone collecte des reserves

      
    this.terresService.getReserve().subscribe(

      // Successful data received

      coffee_shops => {
       // console.log('Resulting data : ' + coffee_shops.data.length);

        this.reserve = coffee_shops.data;
        this.nombreReserve = coffee_shops.data.length;

       // console.log('coffee 1 : ' + this.reserve[0].CODE);

        let i = 0;

        // Construction de notre objet geojson

        for (i = 0; i < coffee_shops.data.length; i++) 
        {
          MapComponent.reserves.features.push({
            "type": "Feature",
            "geometry": {
              "type":JSON.parse(this.reserve[i].geojson).type,
              "coordinates":JSON.parse(this.reserve[i].geojson).coordinates
            },
            "properties": {
              "code": this.reserve[i].CODE,
              "nom": this.reserve[i].NOM,
              "superficie" : this.reserve[i].AREA,
              "perimetre" : this.reserve[i].PERIMETER,
              
            }
          });
        }

        // Fin construction objet geojson

        let drawnObjects = new L.FeatureGroup();

        drawnObjects.addTo(this.map);

        

     //   console.log("geoJson Data : " + JSON.stringify(MapComponent.reserves));

        // geojson marker options
        let geojsonMarkerOptions = {
          radius: 8,
          fillColor: "#1cd1a1",
          color: "#ff6b6b",
          weight: 1,
          opacity: 1,
          fillOpacity: 0.8
        };

      let reserves_map = JSON.stringify(MapComponent.reserves);

      const reserve_map = JSON.parse(reserves_map); // Conversion objet en json

       const reserve_maps =  L.geoJSON(reserve_map, {
          pointToLayer: function (feature, latlng) {
            return L.circleMarker(latlng, geojsonMarkerOptions);
          },
          style : function(feauture)
          {
            return {color: '#4ddbfb', weight:3, fillColor : '#A13678', fillOpacity: 3 };
          },
          onEachFeature: this.onEachFeatureReserve
        });//.addTo(this.map);

        
        //jsonLayer.addData(JSON.parse(coffee_shops_map));
        
        this.map.addLayer(reserve_maps); // Add the geoJson layer to our map so it can be checked by default on control layer

        controlLayers.addOverlay(reserve_maps, "Les Reserves (" + this.nombreReserve + ")");
        
        
        // Création du contenu de la legende

        legend.onAdd = function (map) {

          var div = L.DomUtil.create('div', 'info legend');
          
          div.innerHTML += '<span style="color : #4ddbfb; font-weight : bolder">LEGENDE D\'OCCUPATION DES TERRES</span><br> ';
          div.innerHTML += '<hr><br> ';
          div.innerHTML += '<i style="background: #c51b8a"></i>TERRE OCCUPÉE PAR LES RESERVES<br><br> ';
          div.innerHTML += '<hr> ';
          div.innerHTML += '<i style="background: #4ddbfb"></i> WOLEU NTEM<br><br> ';
          div.innerHTML += '<i style="background: #2ca25f"></i> OGOOUE MARITIME<br><br> ';
          div.innerHTML += '<i style="background: #feb24c"></i> OGOOUE LOLO<br><br> ';
          div.innerHTML += '<i style="background: #f03b20"></i> OGOOUE IVINDO<br><br> ';
          div.innerHTML += '<i style="background: #c994c7"></i> NYANGA<br><br> ';
          div.innerHTML += '<i style="background: #e7e1ef"></i> NGOUNIE<br><br> ';
          div.innerHTML += '<i style="background: #2c7fb8"></i> MOYEN OGOOUE<br><br> ';
          div.innerHTML += '<i style="background: #7fcdbb"></i> HAUTE OGOOUE<br><br> ';
          div.innerHTML += '<i style="background: #edf8b1"></i> ESTUAIRE<br><br> ';

          return div;
      };
      
      legend.addTo(this.map);
      reserve_maps.bringToFront();
      
      // Fin contenu legende avec ajout sur la carte

      },

      error => {
        return this.onHttpError(error);
      }
    );
    
    
    function getColor2(code : string) : string
    {
      switch(code)
      {
        case '100' :
         return '#edf8b1';
        case '200' :
         return '#7fcdbb';
        case '300' :
         return '#2c7fb8';
        case '400' :
         return '#e7e1ef';
        case '500' :
          return '#c994c7';
        case '600' :
          return '#f03b20';
        case '700' :
          return '#feb24c';
        case '800' :
          return '#2ca25f'
        case '900' :
          return '#4ddbfb';
        
      }
    }
    // Fin zone collecte des reserves

  }

  // Fonction pour construire notre objet popup de chaque objet
  onEachFeature(feature : any, layer : any) {
  
    if (feature.properties && feature.properties.nom) {
      layer.bindPopup("<span style='color: red'>Code Province</span> : "
      + feature.properties.code +
      "<br><span style='color: red'>Nom Province</span>   : " + feature.properties.nom + 
      "<br><span style='color: red'>Population</span>   : " + feature.properties.population + 
      "<br><span style='color: red'>Surface (Km2)</span>   : " + feature.properties.surface + 
      "<br><span style='color: red'>Périmetre (Km)</span>   : " + feature.properties.perimetre + 
      "<br><span style='color: purple'>Chef Lieu</span>   : " + feature.properties.chef_lieu + 
      "<br><span style='color: red'>Densité (Km2)</span>   : " + feature.properties.densite + 
      "<br><span style='color: purple'>Population Urbaine</span>   : " + feature.properties.pop_urbaine + 
      "<br><span style='color: purple'>Population Rural</span>   : " + feature.properties.pop_rural); 
    }

    
 }

 onEachFeatureReserve(feature, layer)
 {
   if(feature.properties && feature.properties.code)
   {
    layer.bindPopup("<span style='color: red'>Code reserve</span> : "
      + feature.properties.code +
      "<br><span style='color: red'>Nom reserve</span>   : " + feature.properties.nom + 
      "<br><span style='color: red'>Superficie</span>   : " + feature.properties.superficie + 
      "<br><span style='color: red'>Perimetre</span>   : " + feature.properties.perimetre).openPopup(); 
   }
 }
  onHttpError(errorResponse : any)
  {
    console.log("Error : " + errorResponse);
     
  }

}
