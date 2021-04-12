package utils

import (

	"github.com/sirupsen/logrus"
)

func LoggerInit () {
	logrus.WithFields(logrus.Fields{
		"animal": "walrus",
		"number": 1,
		"size":   10,
	}).Info("A walrus appears")

}