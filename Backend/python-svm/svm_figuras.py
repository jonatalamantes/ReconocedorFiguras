from svmutil import *
import datetime

entradasT = []
salidasT = []

#Ponemos la bandera del comienzo
print ""
print "****** Comienzo con " + str(datetime.datetime.now()) + " **********"
print ""

#Leer los datos del rectangulo
for i in range(36):
    archivo = '../db/Rect/' + str(i+1);
    file = open(archivo, "r")
    instancia = []
    while True:
        letra = file.read(1)
        if not letra:
            break
        elif letra == '\n':
            continue

        instancia.append(int(letra))

    for i in range(5):
        entradasT.append(instancia);
        salidasT.append(1);

for i in range(18):
    archivo = '../db/Sqt/' + str(i+1);
    file = open(archivo, "r")
    instancia = []
    while True:
        letra = file.read(1)
        if not letra:
            break
        elif letra == '\n':
            continue

        instancia.append(int(letra))

    for i in range(5):
        entradasT.append(instancia);
        salidasT.append(2);

for i in range(24):
    archivo = '../db/Tran/' + str(i+1);
    file = open(archivo, "r")
    instancia = []
    while True:
        letra = file.read(1)
        if not letra:
            break
        elif letra == '\n':
            continue

        instancia.append(int(letra))

    for i in range(5):
        entradasT.append(instancia);
        salidasT.append(3);

"""
#Cross Validation a mano
cantPartes = 10
cantPorParte = len(salidasT)/cantPartes
precisiones = []
                   
for posPrueba in range(cantPartes):
    posInicioPrueba = posPrueba*cantPorParte
    posFinPrueba = posInicioPrueba+cantPorParte
    
    #Datos para prueba
    entradasPrueba = entradasT[posInicioPrueba:posFinPrueba]
    salidasPrueba = salidasT[posInicioPrueba:posFinPrueba]
    
    #Datos de entrenamiento
    entradasEntre = entradasT[:]
    salidasEntre = salidasT[:]
    
    #Se borran los datos que se encuentran en los indices de pruebas actuales
    del entradasEntre[posInicioPrueba:posFinPrueba]
    del salidasEntre[posInicioPrueba:posFinPrueba]

    #Prediccion de los datos con solo el accuracity
    prob = svm_problem(salidasEntre,entradasEntre)
    m = svm_train(prob, '-s 0 -t 2 -q')   
    p_label, p_acc, p_val = svm_predict(salidasPrueba, entradasPrueba, m)
    print "Iteracion " + str(posPrueba) + ": " + str(p_acc[0])
    precisiones.append(p_acc[0])

precisionFinal = sum(precisiones)/len(precisiones)
print "Precision Final: " + str(precisionFinal) 
"""

"""
#Cross Validation de la libreria y creacion del modelo
prob  = svm_problem(salidasT, entradasT)
#svm_train(prob, '-s 0 -t 2 -v 2') 
model = svm_train(prob, '-s 0 -t 2 -q') 
svm_save_model('./svm_figuras.model', model); 
"""


#Prueba sobre todas las intancias en el clasificador
m = svm_load_model('./svm_figuras.model');
p_label, p_acc, p_val = svm_predict(salidasT, entradasT, m)
print p_acc 


#Ponemos la bandera del comienzo
print ""
print "****** Termino con " + str(datetime.datetime.now()) + " **********"
print ""