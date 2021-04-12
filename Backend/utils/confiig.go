package utils

import (
	"github.com/joho/godotenv"
	"log"
	"os"
)

func CfgInit () {
	err := godotenv.Load()
	if err != nil {
		log.Fatal("Error loading .env file")
		os.Exit(1)
	}

}

func Get (env string ) (string , error){
	r := os.Getenv(env)
	return r , nil
}

func Set (key string ,env string) (error) {
	err := os.Setenv(key , env)
	return err
}




